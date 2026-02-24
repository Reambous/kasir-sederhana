<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\Product;
use App\Models\SoProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class StockOpnameController extends Controller
{
    public function index()
    {
        // 1. Cek apakah ada Stock Opname yang sedang berjalan (on_progress)
        $activeSO = \App\Models\StockOpname::where('status', 'on_progress')->first();

        // 2. Jika ada, ambil daftar barang yang sedang diopname
        $soProducts = [];
        if ($activeSO) {
            // Menggunakan 'with' agar tidak query berulang-ulang ke tabel products
            $soProducts = $activeSO->soProducts()->with('product')->get();
        }

        // 3. Ambil riwayat Stock Opname yang sudah selesai
        $historySO = \App\Models\StockOpname::where('status', 'done')->latest()->get();

        return view('stock-opnames.index', compact('activeSO', 'soProducts', 'historySO'));
    }
    // Langkah 1: Klik Start Stock Opname
    public function store()
    {
        // Cek apakah ada SO yang masih berjalan (Mencegah error ganda)
        $onProgress = StockOpname::where('status', 'on_progress')->exists();

        if ($onProgress) {
            return back()->with('error', 'Ada stock opname yang masih berjalan!');
        }

        // Buat SO baru
        $code = 'SO-' . date('Ymd') . '-' . strtoupper(uniqid());
        $so = StockOpname::create([
            'code'          => $code,
            'tanggal_mulai' => now()->toDateString(),
            'status'        => 'on_progress'
        ]);

        return back()->with('success', 'Stock Opname berhasil dimulai!');
    }

    // Langkah 2: Klik Sync Data
    public function sync(StockOpname $stockOpname)
    {
        // Validasi keamanan tambahan
        if ($stockOpname->status !== 'on_progress') {
            return back()->with('error', 'Stock Opname ini sudah selesai atau tidak valid.');
        }

        $products = Product::all();

        DB::transaction(function () use ($products, $stockOpname) {
            foreach ($products as $product) {
                // firstOrCreate adalah fungsi sakti Laravel.
                // Sesuai flowchart: "Jika sudah ada datanya, tidak bisa dibuat lagi supaya tidak tertumpuk"
                // Fungsi ini akan mencari data dulu. Jika tidak ada, baru dibuat (insert).
                SoProduct::firstOrCreate(
                    [
                        'stock_opname_id' => $stockOpname->id,
                        'product_id'      => $product->id,
                    ],
                    [
                        'jumlah_awal'  => $product->jumlah,
                        'jumlah_akhir' => null, // Dikosongkan untuk diinput admin nanti
                    ]
                );
            }
        });

        return back()->with('success', 'Data barang berhasil disinkronisasi ke Stock Opname!');
    }

    // Langkah 3: Input Data Aktual
    public function updateItem(Request $request, SoProduct $soProduct)
    {
        // Validasi sederhana agar input tidak minus
        $request->validate([
            'jumlah_akhir' => ['required', 'integer', 'min:0']
        ]);

        $soProduct->update([
            'jumlah_akhir' => $request->jumlah_akhir
        ]);

        return back()->with('success', 'Jumlah aktual barang berhasil disimpan!');
    }

    // Langkah 4: Klik Button Finish
    public function finish(StockOpname $stockOpname)
    {
        if ($stockOpname->status !== 'on_progress') {
            return back()->with('error', 'Hanya Stock Opname on_progress yang bisa diselesaikan.');
        }

        try {
            DB::transaction(function () use ($stockOpname) {
                // Ambil semua barang di SO ini yang 'jumlah_akhir'-nya sudah diisi oleh admin
                $soProducts = $stockOpname->soProducts()->whereNotNull('jumlah_akhir')->get();

                foreach ($soProducts as $item) {
                    // LockForUpdate untuk mencegah bentrok jika kasir sedang bertransaksi di detik yang sama
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if ($product) {
                        // Update kuantitas barang sesuai hasil Stock Opname
                        $product->update(['jumlah' => $item->jumlah_akhir]);
                    }
                }

                // Tutup Stock Opname
                $stockOpname->update(['status' => 'done']);
            });

            return back()->with('success', 'Stock Opname selesai, stok semua barang telah diperbarui!');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyelesaikan Stock Opname.');
        }
    }

    // --- FUNGSI BARU: Batal Stock Opname ---
    public function cancel(\App\Models\StockOpname $stockOpname)
    {
        // Hapus daftar barang yang sedang diopname
        $stockOpname->soProducts()->delete();
        // Hapus sesi stock opname-nya
        $stockOpname->delete();

        return back()->with('success', 'Sesi Stock Opname berhasil dibatalkan dan dihapus.');
    }

    // --- FUNGSI BARU: Simpan Semua Input Sekaligus ---
    public function updateAllItems(\Illuminate\Http\Request $request, \App\Models\StockOpname $stockOpname)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'nullable|numeric|min:0',
        ]);

        // Looping semua inputan dan simpan ke database
        foreach ($request->items as $id => $jumlahAkhir) {
            if ($jumlahAkhir !== null) {
                SoProduct::where('id', $id)
                    ->where('stock_opname_id', $stockOpname->id)
                    ->update(['jumlah_akhir' => $jumlahAkhir]);
            }
        }

        return back()->with('success', 'Semua stok fisik aktual berhasil disimpan!');
    }
}
