<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            {{ __('Katalog Inventaris Gudang') }}
        </h2>
    </x-slot>

    <div class="w-full pb-10">
        <livewire:product-inventory />
    </div>
</x-app-layout>
