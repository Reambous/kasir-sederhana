<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads; // 1. Panggil alat upload
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads; // 2. Tambahkan use ini
    public string $nama = '';
    public string $email = '';
    public $gambar; // 3. Tambahkan ini
    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->nama = Auth::user()->nama;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'gambar' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->fill([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($this->gambar) {
            // Hapus foto lama jika ada
            if ($user->gambar) {
                Storage::disk('public')->delete($user->gambar);
            }
            // Simpan foto baru ke folder 'users'
            $user->gambar = $this->gambar->store('users', 'public');
        }
        $user->save();

        $this->dispatch('profile-updated', name: $user->nama);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div class="flex items-center space-x-6 mb-4">
            <div class="shrink-0">
                @if (auth()->user()->gambar)
                    <img class="h-16 w-16 object-cover rounded-full shadow"
                        src="{{ asset('storage/' . auth()->user()->gambar) }}" alt="Foto Profil">
                @else
                    <div
                        class="h-16 w-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-xl shadow">
                        {{ substr(auth()->user()->gambar, 0, 1) }}
                    </div>
                @endif
            </div>
            <label class="block">
                <span class="sr-only">Pilih Foto Profil</span>
                <input type="file" wire:model="gambar" accept="image/*"
                    class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100
                    " />
            </label>
            <div wire:loading wire:target="gambar" class="text-sm text-gray-500">Mengunggah...</div>
        </div>
        <div>
            <x-input-label for="nama" :value="__('Nama')" />
            <x-text-input wire:model="nama" id="nama" name="nama" type="text" class="mt-1 block w-full"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
