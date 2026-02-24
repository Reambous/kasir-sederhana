<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard (Ringkasan Toko)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600">Total Macam Barang</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $totalProducts ?? 0 }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600">Transaksi Hari Ini</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $totalOrders ?? 0 }} <span
                                class="text-sm font-normal text-gray-500">struk</span></p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600">Stok Menipis (< 10)</p>
                                <p class="text-2xl font-semibold text-red-600">{{ $lowStock ?? 0 }} <span
                                        class="text-sm font-normal text-gray-500">barang</span></p>
                    </div>
                </div>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Selamat Datang, {{ auth()->user()->nama }}!</h3>
                <p class="text-gray-500 mb-6">Gunakan menu navigasi di atas untuk mulai mengelola toko.</p>
                <a href="{{ route('pos') }}"
                    class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow transition">
                    Buka Mesin Kasir
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
