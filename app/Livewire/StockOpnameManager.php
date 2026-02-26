<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockOpname;
use App\Models\SoProduct; // Pastikan model ini dipanggil

class StockOpnameManager extends Component
{
    use WithPagination;

    public $searchActive = '';
    public $searchHistory = '';

    public function updatingSearchActive()
    {
        $this->resetPage('activePage');
    }
    public function updatingSearchHistory()
    {
        $this->resetPage('historyPage');
    }

    // --- FUNGSI AUTO-SAVE ---
    public function updateStok($itemId, $jumlahAkhir)
    {
        // Simpan otomatis saat angka stok diketik
        $item = SoProduct::find($itemId);
        if ($item && $jumlahAkhir !== '') {
            $item->update(['jumlah_akhir' => $jumlahAkhir]);
        }
    }

    public function updateKeterangan($itemId, $keterangan)
    {
        // Simpan otomatis saat keterangan diketik
        $item = SoProduct::find($itemId);
        if ($item) {
            $item->update(['keterangan' => $keterangan]);
        }
    }
    // -------------------------

    public function render()
    {
        // 1. DATA SESI AKTIF (Sekarang pakai Pagination & Search Livewire!)
        $activeSO = StockOpname::where('status', 'on_progress')->latest()->first(); // Sesuaikan kata 'berjalan' dengan databasemu
        $activeProducts = collect();

        if ($activeSO) {
            $queryActive = $activeSO->soProducts()->with('product');
            if ($this->searchActive) {
                $queryActive->whereHas('product', function ($q) {
                    $q->where('nama', 'like', '%' . $this->searchActive . '%')
                        ->orWhere('barcode', 'like', '%' . $this->searchActive . '%');
                });
            }
            // Kita potong 10 baris per halaman (pakai nama page khusus agar tidak bentrok dengan riwayat)
            $activeProducts = $queryActive->paginate(10, ['*'], 'activePage');
        }

        // 2. DATA RIWAYAT
        $historyQuery = StockOpname::where('status', 'done')->latest(); // Sesuaikan kata 'selesai' dengan databasemu
        if ($this->searchHistory) {
            $historyQuery->where('code', 'like', '%' . $this->searchHistory . '%');
        }
        $historySO = $historyQuery->paginate(5, ['*'], 'historyPage');

        return view('livewire.stock-opname-manager', compact('activeSO', 'activeProducts', 'historySO'));
    }
}
