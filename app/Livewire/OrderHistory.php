<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class OrderHistory extends Component
{
    use WithPagination;

    public $search = '';

    // Reset halaman ke 1 setiap kali kasir mengetik huruf baru
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Di Class Livewire kamu
    public function deleteOrder($id)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($id) {
                // Karena sudah di-set di Model, findOrFail sekarang akan mengenali UUID
                $order = \App\Models\Order::with('items.product')->findOrFail($id);

                // Kembalikan stok
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('jumlah', $item->jumlah);
                    }
                }

                $order->delete();
            });

            session()->flash('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            // Ini akan membantu kita tahu kalau masih ada yang salah
            session()->flash('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    public function deleteThisWeekOrders()
    {
        // Mengambil rentang awal dan akhir minggu ini
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $orders = \App\Models\Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();

        foreach ($orders as $order) {
            // Kembalikan stok untuk setiap barang dalam order
            foreach ($order->items as $item) {
                $item->product->increment('jumlah', $item->jumlah);
            }
            $order->delete();
        }

        session()->flash('success', 'Semua transaksi minggu ini berhasil dibersihkan.');
    }

    public function render()
    {
        // 1. Siapkan query dasar
        $query = Order::with(['user', 'items'])->latest();

        // 2. Filter pencarian secara real-time
        if ($this->search) {
            $query->where('code', 'like', '%' . $this->search . '%')
                ->orWhereHas('user', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                });
        }

        // 3. Hitung total pendapatan berdasarkan hasil filter
        $semuaOrder = $query->get();
        $totalPendapatan = $semuaOrder->sum(function ($order) {
            $totalBelanja = $order->items->sum(function ($item) {
                return $item->harga_jual * $item->jumlah;
            });
            return $totalBelanja - $order->potongan;
        });

        // 4. Potong data menjadi 10 per halaman
        $orders = $query->paginate(10);

        return view('livewire.order-history', compact('orders', 'totalPendapatan'));
    }
}
