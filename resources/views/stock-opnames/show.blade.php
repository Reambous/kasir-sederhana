<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Laporan Stock Opname') }}
            </h2>
            <a href="{{ route('stock-opnames.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded shadow-sm transition text-sm">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <livewire:stock-opname-detail :stockOpnameId="$stockOpname->id" />
        </div>
    </div>
</x-app-layout>
