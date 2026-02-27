<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Tag;

class ProductInventory extends Component
{
    use WithPagination;

    public $search = '';

    // Kembalikan ke halaman 1 jika admin mengetik pencarian baru
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Panggil semua tag untuk ditampilkan di form Tambah/Edit
        $tags = Tag::all();

        // Query pencarian barang
        $query = Product::with('tags');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        }

        // ==========================================
        // FITUR BARU: URUTKAN STOK HABIS/SEDIKIT KE ATAS
        // ==========================================
        // Prioritas 1: Urutkan dari jumlah stok paling kecil (0) ke paling besar
        // Prioritas 2: Jika stoknya sama, urutkan berdasarkan yang paling baru ditambahkan (latest)
        $query->orderBy('jumlah', 'asc')->latest();

        // Potong data menjadi 10 baris per halaman
        $products = $query->paginate(20);

        return view('livewire.product-inventory', [
            'products' => $products,
            'tags' => $tags
        ]);
    }
}
