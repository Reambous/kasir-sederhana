<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">

        <div class="border-b-2 border-slate-100 pb-4 mb-6">
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">System Login</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">Masukkan kredensial otoritas Anda.</p>
        </div>

        <div class="space-y-2">
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email
                Akses</label>
            <input wire:model="form.email" id="email" type="email" required autofocus autocomplete="username"
                class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 text-slate-900 text-sm focus:ring-0 focus:border-indigo-600 focus:bg-white transition-colors rounded-none shadow-sm"
                placeholder="admin@pos.com">
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-xs text-rose-600 font-bold" />
        </div>

        <div class="space-y-2">
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata
                Sandi</label>
            <input wire:model="form.password" id="password" type="password" required autocomplete="current-password"
                class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 text-slate-900 text-sm focus:ring-0 focus:border-indigo-600 focus:bg-white transition-colors rounded-none shadow-sm"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-xs text-rose-600 font-bold" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <label for="remember" class="inline-flex items-center cursor-pointer group">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="w-5 h-5 border-2 border-slate-300 text-indigo-600 focus:ring-indigo-600 rounded-none shadow-sm cursor-pointer group-hover:border-indigo-500 transition-colors">
                <span
                    class="ml-2 text-sm text-slate-600 font-semibold group-hover:text-slate-900 transition-colors">Ingat
                    Sesi Saya</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" wire:loading.attr="disabled"
                class="relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 rounded-none shadow-md transition-all active:scale-[0.98] disabled:opacity-70 disabled:cursor-wait">

                <span wire:loading.remove wire:target="login">Otorisasi Masuk</span>

                <span wire:loading wire:target="login" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Memverifikasi...
                </span>

            </button>
        </div>
    </form>
</div>
