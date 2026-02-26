<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            // Memulai DB Transaction. Jika ada 1 saja yang error, SEMUA DIBATALKAN.
            DB::transaction(function () use ($request) {
                $data = $request->validated();

                // Bikin kode transaksi otomatis (Misal: INV-20231025-XXXX)
                $orderCode = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());

                // 1. Buat Data Induk Order (Transaksi)
                $order = Order::create([
                    'code'              => $orderCode,
                    'tanggal'           => now()->toDateString(),
                    'nama_buyer'        => $data['nama_buyer'],
                    'metode_pembayaran' => $data['metode_pembayaran'],
                    'uang_diterima'     => $data['uang_diterima'],
                    'potongan'          => $data['potongan'] ?? 0,
                    'status'            => 'done', // Langsung done karena bayar di kasir
                    'user_id'           => Auth::id() ?? '123e4567-e89b-12d3-a456-426614174000', // ID kasir
                ]);

                // 2. Looping barang yang dibeli
                foreach ($data['items'] as $item) {
                    // Cari produk di database (LockForUpdate mencegah bentrok jika 2 kasir beli barang yg sama di detik yg sama)
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    // Cek apakah stok cukup?
                    if ($product->jumlah < $item['jumlah']) {
                        // Jika stok kurang, lemparkan error! Transaksi akan otomatis di-rollback (dibatalkan)
                        throw new Exception("Stok barang {$product->nama} tidak mencukupi!");
                    }

                    // 3. Simpan ke OrderItem (Snapshot harga dan nama saat itu juga)
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'nama'       => $product->nama, // Simpan nama agar jika nanti nama diubah admin, di struk ini tidak berubah
                        'harga_jual' => $product->harga_jual, // Simpan harga mati saat dibeli
                        'jumlah'     => $item['jumlah'],
                    ]);

                    // 4. Kurangi stok barang di database
                    $product->decrement('jumlah', $item['jumlah']);
                }
            });

            return back()->with('success', 'Transaksi berhasil disimpan!');
        } catch (Exception $e) {
            // Jika masuk ke sini (misal stok kurang), Laravel otomatis tidak menyimpan data apapun ke DB
            return back()->with('error', $e->getMessage());
        }
    }
    // Fungsi untuk Export / Cetak Invoice
    public function export(Order $order)
    {
        // 1. Sistem get transaksi_item (Mengambil data relasi secara efisien menggunakan 'load')
        // Ini menghindari query berulang-ulang ke database (N+1 problem)
        $order->load('items');

        // 2. Hitung total harga barang murni (sebelum potongan)
        // Kita gunakan fungsi bawaan koleksi Laravel 'sum()' agar tidak perlu repot pakai foreach manual
        $total_harga_barang = $order->items->sum(function ($item) {
            return $item->harga_jual * $item->jumlah;
        });

        // 3. Hitung total dikurangi potongan
        $total_bayar = $total_harga_barang - $order->potongan;

        // 4. Hitung kembalian (uang diterima - total bayar)
        $kembalian = $order->uang_diterima - $total_bayar;

        // 5. Export ke tampilan (View) khusus untuk dicetak
        return view('orders.invoice', [
            'order'              => $order,
            'total_harga_barang' => $total_harga_barang,
            'total_bayar'        => $total_bayar,
            'kembalian'          => $kembalian
        ]);
    }
}
