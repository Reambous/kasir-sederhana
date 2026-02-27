<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-slate-900 uppercase tracking-wider">
            {{ __('Stock Opname (Penyesuaian Stok)') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full px-2 sm:px-4">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}</div>
            @endif

            <livewire:stock-opname-manager />
        </div>
    </div>
</x-app-layout>
