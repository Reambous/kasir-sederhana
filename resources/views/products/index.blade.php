<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Barang (Gudang)') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        showAdd: false,
        showEdit: false,
        editUrl: '',
        form: { nama: '', barcode: '', harga_beli: '', harga_jual: '', jumlah: '', tags: [] }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    Terdapat kesalahan pada input form Anda. Pastikan barcode tidak kembar.
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-700">Daftar Inventaris</h3>
                    <button @click="showAdd = true"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition">
                        + Tambah Barang
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Nama Barang</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Barcode</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Stok</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Harga Jual</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-3 px-4">{{ $product->nama }}</td>
                                    <td class="py-3 px-4 text-gray-500 text-sm">{{ $product->barcode }}</td>
                                    <td
                                        class="py-3 px-4 font-bold {{ $product->jumlah < 10 ? 'text-red-500' : 'text-green-600' }}">
                                        {{ $product->jumlah }}
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-indigo-600">Rp
                                        {{ number_format($product->harga_jual, 0, ',', '.') }}</td>

                                    <td class="py-3 px-4 text-center space-x-2">

                                        <button
                                            @click="
                                            form.nama = '{{ addslashes($product->nama) }}';
                                            form.barcode = '{{ addslashes($product->barcode) }}';
                                            form.harga_beli = '{{ $product->harga_beli }}';
                                            form.harga_jual = '{{ $product->harga_jual }}';
                                            form.jumlah = '{{ $product->jumlah }}';
                                            form.tags = {{ $product->tags->pluck('id')->toJson() }};
                                            editUrl = '{{ route('products.update', $product->id) }}';
                                            showEdit = true;
                                        "
                                            class="text-blue-600 hover:text-blue-800 font-medium px-2">
                                            Edit
                                        </button>

                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ addslashes($product->nama) }}? Data ini tidak bisa dikembalikan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 font-medium px-2">
                                                Hapus
                                            </button>
                                        </form>

                                    </td>
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

        <div x-show="showAdd" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAdd" @click="showAdd = false"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="showAdd"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Input Barang Baru</h3>
                            <div class="space-y-4">
                                <div><label class="block text-sm font-medium text-gray-700">Nama</label><input
                                        type="text" name="nama" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Foto Barang
                                        (Opsional)</label>
                                    <input type="file" name="gambar" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700">Barcode</label><input
                                        type="text" name="barcode" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-sm font-medium text-gray-700">Harga Beli</label><input
                                            type="number" name="harga_beli" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                    <div><label class="block text-sm font-medium text-gray-700">Harga Jual</label><input
                                            type="number" name="harga_jual" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700">Jumlah Awal</label><input
                                        type="number" name="jumlah" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Tag
                                        (Opsional)</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($tags as $tag)
                                            <label
                                                class="inline-flex items-center bg-white border border-gray-300 px-3 py-1 rounded-full cursor-pointer hover:bg-indigo-50 transition">
                                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">{{ $tag->nama }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end space-x-2">
                            <button type="button" @click="showAdd = false"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</button>
                            <button type="submit"
                                class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md text-white hover:bg-indigo-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showEdit" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEdit" @click="showEdit = false"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="showEdit"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <form x-bind:action="editUrl" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Data Barang</h3>
                            <div class="space-y-4">
                                <div><label class="block text-sm font-medium text-gray-700">Nama</label><input
                                        type="text" name="nama" x-model="form.nama" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Foto Barang
                                        (Opsional)</label>
                                    <input type="file" name="gambar" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700">Barcode</label><input
                                        type="text" name="barcode" x-model="form.barcode" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-sm font-medium text-gray-700">Harga
                                            Beli</label><input type="number" name="harga_beli"
                                            x-model="form.harga_beli" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                    <div><label class="block text-sm font-medium text-gray-700">Harga
                                            Jual</label><input type="number" name="harga_jual"
                                            x-model="form.harga_jual" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700">Jumlah Stok</label><input
                                        type="number" name="jumlah" x-model="form.jumlah" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Tag</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($tags as $tag)
                                            <label
                                                class="inline-flex items-center bg-white border border-gray-300 px-3 py-1 rounded-full cursor-pointer hover:bg-indigo-50 transition">
                                                <input type="checkbox" name="tags[]" :value="{{ $tag->id }}"
                                                    x-model="form.tags"
                                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">{{ $tag->nama }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end space-x-2">
                            <button type="button" @click="showEdit = false"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</button>
                            <button type="submit"
                                class="bg-blue-600 py-2 px-4 border border-transparent rounded-md text-white hover:bg-blue-700">Update
                                Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
