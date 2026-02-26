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

        // Query pencarian barang berdasarkan nama atau barcode
        $query = Product::with('tags')->latest();

        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%');
        }

        // Potong data menjadi 10 baris per halaman
        $products = $query->paginate(10);

        return view('livewire.product-inventory', [
            'products' => $products,
            'tags' => $tags
        ]);
    }
}
