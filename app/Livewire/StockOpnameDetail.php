<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockOpname;

class StockOpnameDetail extends Component
{
    use WithPagination;

    public $stockOpnameId;
    public $search = '';

    public function mount($stockOpnameId)
    {
        $this->stockOpnameId = $stockOpnameId;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $stockOpname = StockOpname::findOrFail($this->stockOpnameId);

        $query = $stockOpname->soProducts()->with('product');

        if ($this->search) {
            $query->whereHas('product', function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        }

        // ==========================================
        // MANTRA SQL SUPER: URUTKAN SELISIH TERBESAR KE ATAS
        // ==========================================
        // 1. reorder() : Menghapus semua urutan bawaan dari Model
        // 2. orderByRaw : Menghitung selisih (ABS = nilai mutlak agar minus jadi plus) 
        //    lalu diurutkan DESC (paling besar ke paling kecil)
        // 3. COALESCE : Mengubah NULL menjadi angka 0 agar tidak error saat dihitung

        $query->reorder()
            ->orderByRaw('ABS(jumlah_awal - COALESCE(jumlah_akhir, jumlah_awal)) DESC')
            ->orderBy('id', 'asc'); // Jika selisihnya sama-sama 0, urutkan rapi berdasarkan ID

        $items = $query->paginate(10);

        return view('livewire.stock-opname-detail', compact('stockOpname', 'items'));
    }
}
