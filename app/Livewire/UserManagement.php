<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;

    // Form Data
    public $userId = null;
    public $nama, $email, $password, $role = 'kasir';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->nama = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'kasir';
        $this->resetValidation();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->nama = $user->nama;
        $this->email = $user->email;
        $this->role = $user->role;
        // Password sengaja dikosongkan saat edit

        $this->isModalOpen = true;
    }

    public function store()
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'role' => 'required|in:admin,gudang,kasir',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userId)],
        ];

        // Jika tambah akun baru, password wajib. Jika edit, opsional.
        if (!$this->userId) {
            $rules['password'] = 'required|min:6';
        } else {
            $rules['password'] = 'nullable|min:6';
        }

        $this->validate($rules);

        $data = [
            'nama' => $this->nama,
            'email' => $this->email,
            'role' => $this->role,
        ];

        // Hash password jika diisi
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        session()->flash('success', $this->userId ? 'Akun berhasil diperbarui!' : 'Akun baru berhasil dibuat!');
        $this->closeModal();
    }

    public function delete($id)
    {
        // Gunakan auth()->id() untuk mendapatkan ID user yang sedang login
        if (Auth::id() === $id) {
            session()->flash('error', 'Gagal: Anda tidak bisa menghapus akun Anda sendiri!');
            return;
        }

        User::findOrFail($id)->delete();
        session()->flash('success', 'Akun berhasil dihapus permanen.');
    }

    public function render()
    {
        $users = User::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.user-management', [
            'users' => $users
        ]);
    }
}
