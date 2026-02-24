<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tag; // Tambahkan ini untuk memanggil Tag
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class PosKasir extends Component
{
    public $search = '';
    public $selectedTags = []; // <-- Variabel penampung Tag yang dicentang
    public $cart = [];
    public $uang_diterima = 0;
    public $potongan = 0;
    public $metode_pembayaran = 'cash';
    public $nama_buyer = '';
    public $lastOrderId = null;

    // --- FUNGSI KERANJANG SEBELUMNYA (TETAP SAMA) ---
    public function addToCart($productId)
    { /* ... isi dari fungsi sebelumnya biarkan sama ... */
        $product = Product::find($productId);

        if (!$product || $product->jumlah <= 0) {
            session()->flash('error', 'Stok barang habis!');
            return;
        }

        if (array_key_exists($productId, $this->cart)) {
            if ($this->cart[$productId]['jumlah'] >= $product->jumlah) {
                session()->flash('error', 'Stok tidak mencukupi!');
                return;
            }
            $this->cart[$productId]['jumlah']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'nama' => $product->nama,
                'harga_jual' => $product->harga_jual,
                'jumlah' => 1,
            ];
        }
    }

    public function decreaseQty($productId)
    { /* ... isi fungsi sebelumnya ... */
        if ($this->cart[$productId]['jumlah'] > 1) {
            $this->cart[$productId]['jumlah']--;
        } else {
            unset($this->cart[$productId]);
        }
    }

    // --- FUNGSI BARU UNTUK KETIK MANUAL & HAPUS BARANG ---

    // Fungsi Hapus total dari keranjang
    public function removeItem($productId)
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
        }
    }

    // Fungsi Update saat kasir mengetik angka
    public function updateQty($productId, $qty)
    {
        $qty = (int) $qty; // Pastikan jadi angka

        if ($qty <= 0) {
            $this->removeItem($productId);
            return;
        }

        $product = Product::find($productId);
        if ($product) {
            if ($qty > $product->jumlah) {
                // Jika ketik melebihi stok, mentokkan ke maksimal stok
                session()->flash('error', 'Stok ' . $product->nama . ' hanya tersisa ' . $product->jumlah . '!');
                $this->cart[$productId]['jumlah'] = $product->jumlah;
            } else {
                $this->cart[$productId]['jumlah'] = $qty;
            }
        }
    }

    // --- FUNGSI TOTAL & CHECKOUT SEBELUMNYA (TETAP SAMA) ---
    public function getTotalProperty()
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['harga_jual'] * $item['jumlah'];
        }
        return $total - (empty($this->potongan) ? 0 : $this->potongan);
    }

    public function checkout()
    {
        // ... (Isi fungsi checkout persis seperti yang terakhir kita buat dengan $newOrderId) ...
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang kosong!');
            return;
        }

        if ($this->uang_diterima < $this->total) {
            session()->flash('error', 'Uang tidak cukup!');
            return;
        }

        try {
            $newOrderId = null;

            DB::transaction(function () use (&$newOrderId) {
                $orderCode = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());

                $order = Order::create([
                    'code'              => $orderCode,
                    'tanggal'           => now()->toDateString(),
                    'nama_buyer'        => $this->nama_buyer,
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'uang_diterima'     => $this->uang_diterima,
                    'potongan'          => $this->potongan ?: 0,
                    'status'            => 'done',
                    'user_id'           => Auth::id(),
                ]);

                $newOrderId = $order->id;

                foreach ($this->cart as $item) {
                    $product = Product::lockForUpdate()->find($item['id']);

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'nama'       => $product->nama,
                        'harga_jual' => $product->harga_jual,
                        'jumlah'     => $item['jumlah'],
                    ]);

                    $product->decrement('jumlah', $item['jumlah']);
                }
            });

            $this->cart = [];
            $this->uang_diterima = 0;
            $this->potongan = 0;
            $this->nama_buyer = '';
            $this->lastOrderId = $newOrderId;

            session()->flash('success', 'Transaksi Berhasil!');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // 1. Ambil semua Tag untuk ditampilkan di tombol filter
        $allTags = Tag::all();

        // 2. Query Pencarian Barang (Sekarang mendukung Filter Tag)
        $query = Product::where(function ($q) {
            $q->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%');
        });

        // Jika ada Tag yang dicentang, filter produk yang punya Tag tersebut
        if (!empty($this->selectedTags)) {
            $query->whereHas('tags', function ($q) {
                $q->whereIn('tags.id', $this->selectedTags);
            });
        }

        $products = $query->get();

        return view('livewire.pos-kasir', [
            'products' => $products,
            'allTags' => $allTags
        ]);
    }
}
