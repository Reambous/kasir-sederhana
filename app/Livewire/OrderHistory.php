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
