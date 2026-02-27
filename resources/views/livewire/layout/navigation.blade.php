<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-slate-900 border-b-4 border-indigo-600">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-white font-black text-xl tracking-wider uppercase hidden sm:block">POS<span
                                class="text-indigo-500">SYS</span></span>
                    </a>
                </div>

                <div class="hidden space-x-1 sm:-my-px sm:ml-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="text-slate-300 hover:text-white focus:text-white uppercase text-xs font-bold tracking-wider px-3 border-transparent hover:bg-slate-800 transition-colors">
                        Dashboard
                    </x-nav-link>

                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'gudang')
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')"
                            class="text-slate-300 hover:text-white focus:text-white uppercase text-xs font-bold tracking-wider px-3 border-transparent hover:bg-slate-800 transition-colors">
                            Inventaris
                        </x-nav-link>
                        <x-nav-link :href="route('tags.index')" :active="request()->routeIs('tags.*')"
                            class="text-slate-300 hover:text-white focus:text-white uppercase text-xs font-bold tracking-wider px-3 border-transparent hover:bg-slate-800 transition-colors">
                            Kategori
                        </x-nav-link>
                        <x-nav-link :href="route('stock-opnames.index')" :active="request()->routeIs('stock-opnames.*')"
                            class="text-slate-300 hover:text-white focus:text-white uppercase text-xs font-bold tracking-wider px-3 border-transparent hover:bg-slate-800 transition-colors">
                            Opname
                        </x-nav-link>
                    @endif

                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kasir')
                        <x-nav-link :href="route('pos')" :active="request()->routeIs('pos')"
                            class="text-emerald-400 hover:text-emerald-300 focus:text-emerald-300 uppercase text-xs font-black tracking-wider px-3 border-transparent hover:bg-slate-800 transition-colors">
                            Mesin POS
                        </x-nav-link>
                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')"
                            class="text-slate-300 hover:text-white focus:text-white uppercase text-xs font-bold tracking-wider px-3 border-transparent hover:bg-slate-800 transition-colors">
                            Transaksi
                        </x-nav-link>
                    @endif

                    @if (auth()->user()->role === 'admin')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')"
                            class="text-indigo-400 hover:text-indigo-300 focus:text-indigo-300 uppercase text-xs font-black tracking-wider px-3 border-transparent hover:bg-slate-800 transition-colors">
                            Pengguna
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-black uppercase tracking-widest text-slate-300 hover:text-white focus:outline-none transition ease-in-out duration-150">

                            <div class="mr-3">
                                @if (Auth::user()->gambar)
                                    <img src="{{ asset('storage/' . Auth::user()->gambar) }}"
                                        class="w-8 h-8 rounded-none border-2 border-indigo-500 object-cover shadow-[2px_2px_0px_rgba(0,0,0,1)]">
                                @else
                                    <div
                                        class="w-8 h-8 bg-slate-700 border-2 border-indigo-500 flex items-center justify-center text-[10px] font-black text-indigo-400 shadow-[2px_2px_0px_rgba(0,0,0,1)]">
                                        {{ substr(Auth::user()->nama, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <div>{{ Auth::user()->nama }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-white border-2 border-slate-900 shadow-xl rounded-none">
                            <x-dropdown-link :href="route('profile')" wire:navigate
                                class="hover:bg-slate-100 font-bold text-slate-800 uppercase text-xs tracking-wider">
                                {{ __('Profil Akun') }}
                            </x-dropdown-link>

                            <button wire:click="logout" class="w-full text-left">
                                <x-dropdown-link
                                    class="hover:bg-rose-50 text-rose-600 font-bold uppercase text-xs tracking-wider border-t border-slate-100">
                                    {{ __('Otorisasi Keluar') }}
                                </x-dropdown-link>
                            </button>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none focus:bg-slate-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-slate-800 border-t border-slate-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="text-white font-bold uppercase tracking-wider text-sm">
                Dashboard
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-slate-700">
            <div class="px-4">
                <div class="font-bold text-base text-white" x-data="{{ json_encode(['name' => auth()->user()->nama]) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-slate-400">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate
                    class="text-slate-300 font-bold uppercase text-xs">
                    Profil Akun
                </x-responsive-nav-link>

                <button wire:click="logout" class="w-full text-left">
                    <x-responsive-nav-link class="text-rose-500 font-bold uppercase text-xs">
                        Otorisasi Keluar
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
