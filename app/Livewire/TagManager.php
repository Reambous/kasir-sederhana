<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tag;

class TagManager extends Component
{
    use WithPagination;

    public $search = '';

    // Otomatis kembali ke halaman 1 saat admin mengetik pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query dasar
        $query = Tag::latest();

        // Filter pencarian berdasarkan nama tag
        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%');
        }

        // Potong menjadi 10 baris per halaman
        $tags = $query->paginate(10);

        return view('livewire.tag-manager', compact('tags'));
    }
}
