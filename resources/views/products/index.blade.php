<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Barang (Gudang)') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    Terdapat kesalahan pada input form Anda.
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-end mb-4">
                    <button @click="openModal = true"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                        + Tambah Barang Baru
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Nama Barang</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Barcode</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Stok</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Harga Beli</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Harga Jual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $product->nama }}</td>
                                    <td class="py-3 px-4 text-gray-500 text-sm">{{ $product->barcode }}</td>
                                    <td
                                        class="py-3 px-4 font-bold {{ $product->jumlah < 10 ? 'text-red-500' : 'text-green-600' }}">
                                        {{ $product->jumlah }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-500">Rp
                                        {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 font-semibold">Rp
                                        {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400">Belum ada data barang di
                                        gudang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="openModal" @click="openModal = false"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="openModal"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Input Barang
                                Baru</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                                    <input type="text" name="nama" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Barcode</label>
                                    <input type="text" name="barcode" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Harga Beli (Rp)</label>
                                        <input type="number" name="harga_beli" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Harga Jual (Rp)</label>
                                        <input type="number" name="harga_jual" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah Stok Awal</label>
                                    <input type="number" name="jumlah" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end space-x-2">
                            <button type="button" @click="openModal = false"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Batal</button>
                            <button type="submit"
                                class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none">Simpan
                                Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
