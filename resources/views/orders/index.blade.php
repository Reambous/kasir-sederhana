<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-slate-900 uppercase tracking-wider">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full px-2 sm:px-4"> <livewire:order-history />
        </div>
    </div>
</x-app-layout>
