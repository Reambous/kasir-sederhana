<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class PosKasir extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedTags = [];
    public $cart = [];
    public $uang_diterima = 0;
    public $potongan = 0;
    public $metode_pembayaran = 'cash';
    public $nama_buyer = '';
    public $lastOrderId = null;

    // --- 1. UPDATE: CEGAT KELEBIHAN STOK SAAT KLIK [+] ---
    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->jumlah <= 0) {
            session()->flash('error', 'Stok barang habis!');
            return;
        }

        if (array_key_exists($productId, $this->cart)) {
            // Cek apakah jumlah di keranjang sudah mencapai batas stok
            if ($this->cart[$productId]['jumlah'] >= $product->jumlah) {
                session()->flash('error', 'Stok ' . $product->nama . ' hanya tersisa ' . $product->jumlah . '!');
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
        $this->syncUangDiterima();
    }

    public function decreaseQty($productId)
    {
        if ($this->cart[$productId]['jumlah'] > 1) {
            $this->cart[$productId]['jumlah']--;
        } else {
            unset($this->cart[$productId]);
        }
        $this->syncUangDiterima();
    }

    public function removeItem($productId)
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
        }
        $this->syncUangDiterima();
    }

    // --- 2. UPDATE: CEGAT KETIK MANUAL MELEBIHI STOK / MINUS ---
    public function updateQty($productId, $qty)
    {
        $qty = (int) $qty;

        if ($qty <= 0) {
            $this->removeItem($productId);
            return;
        }

        $product = Product::find($productId);
        if ($product) {
            if ($qty > $product->jumlah) {
                // Beri peringatan, TAPI biarkan angka yang diketik tetap masuk ke keranjang
                session()->flash('error', 'Peringatan! Ketikanmu melebihi stok ' . $product->nama . ' (Sisa: ' . $product->jumlah . ').');
                $this->cart[$productId]['jumlah'] = $qty;
            } else {
                $this->cart[$productId]['jumlah'] = $qty;
            }
        }
        $this->syncUangDiterima();
    }

    // --- 3. FITUR BARU: KOSONGKAN KERANJANG (BATAL) ---
    public function clearCart()
    {
        $this->cart = [];
        $this->nama_buyer = '';
        $this->potongan = 0;
        $this->uang_diterima = 0;
        $this->metode_pembayaran = 'cash';

        session()->flash('success', 'Keranjang berhasil dibatalkan dan dikosongkan.');
    }

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
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang kosong!');
            return;
        }

        // ==========================================
        // SATPAM TERAKHIR: Hanya Peringatkan & Tolak!
        // ==========================================
        foreach ($this->cart as $item) {
            $product = Product::find($item['id']);

            if (!$product) {
                session()->flash('error', 'Transaksi Dibatalkan! Barang ' . $item['nama'] . ' sudah tidak ada di gudang.');
                return;
            }

            // Jika jumlah di keranjang melebihi stok gudang
            if ($item['jumlah'] > $product->jumlah) {
                // Munculkan error dan langsung HENTIKAN proses bayar
                session()->flash('error', 'GAGAL BAYAR! Stok ' . $item['nama'] . ' tidak cukup (Sisa: ' . $product->jumlah . '). Tolong kurangi jumlahnya secara manual.');
                return;
            }
        }
        // ==========================================

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

    public function updatedMetodePembayaran($value)
    {
        if ($value === 'non_cash') {
            $this->uang_diterima = $this->total;
        } else {
            $this->uang_diterima = 0;
        }
    }

    public function updatedPotongan()
    {
        $this->syncUangDiterima();
    }

    private function syncUangDiterima()
    {
        if ($this->metode_pembayaran === 'non_cash') {
            $this->uang_diterima = $this->total;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedTags()
    {
        $this->resetPage();
    }

    public function render()
    {
        $allTags = Tag::all();

        $query = Product::where(function ($q) {
            $q->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%');
        });

        if (!empty($this->selectedTags)) {
            $query->whereHas('tags', function ($q) {
                $q->whereIn('tags.id', $this->selectedTags);
            });
        }

        $products = $query->paginate(12);

        return view('livewire.pos-kasir', [
            'products' => $products,
            'allTags' => $allTags
        ]);
    }
}
