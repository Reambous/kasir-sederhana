<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class PosKasir extends Component
{
    public $search = '';
    public $cart = []; // Menyimpan daftar belanja
    public $uang_diterima = 0;
    public $potongan = 0;
    public $metode_pembayaran = 'cash';
    public $nama_buyer = '';

    // Fungsi untuk menambah barang ke keranjang
    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->jumlah <= 0) {
            session()->flash('error', 'Stok barang habis!');
            return;
        }

        // Cek apakah barang sudah ada di keranjang
        if (array_key_exists($productId, $this->cart)) {
            // Cek limit stok
            if ($this->cart[$productId]['jumlah'] >= $product->jumlah) {
                session()->flash('error', 'Stok tidak mencukupi!');
                return;
            }
            $this->cart[$productId]['jumlah']++;
        } else {
            // Tambah barang baru ke keranjang
            $this->cart[$productId] = [
                'id' => $product->id,
                'nama' => $product->nama,
                'harga_jual' => $product->harga_jual,
                'jumlah' => 1,
            ];
        }
    }

    // Fungsi mengurangi/menghapus dari keranjang
    public function decreaseQty($productId)
    {
        if ($this->cart[$productId]['jumlah'] > 1) {
            $this->cart[$productId]['jumlah']--;
        } else {
            unset($this->cart[$productId]); // Hapus jika qty 0
        }
    }

    // Menghitung total belanja secara real-time
    public function getTotalProperty()
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['harga_jual'] * $item['jumlah'];
        }
        return $total - (empty($this->potongan) ? 0 : $this->potongan);
    }

    // Fungsi Checkout (Mengeksekusi DB Transaction yang sudah kita buat sebelumnya)
    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang kosong!');
            return;
        }

        if ($this->uang_diterima < $this->total) {
            session()->flash('error', 'Uang tidak cukup!');
            return;
        }

        try {
            DB::transaction(function () {
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

            // Kosongkan form setelah sukses
            $this->cart = [];
            $this->uang_diterima = 0;
            $this->potongan = 0;
            $this->nama_buyer = '';

            session()->flash('success', 'Transaksi Berhasil!');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Fitur pencarian barang secara real-time
        $products = Product::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('barcode', 'like', '%' . $this->search . '%')
            ->get();

        return view('livewire.pos-kasir', [
            'products' => $products
        ]);
    }
}
