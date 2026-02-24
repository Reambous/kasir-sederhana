<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Kategori / Tag') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showEdit: false, editUrl: '', editNama: '' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Nama Tag tidak boleh kosong
                    atau duplikat.</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <form action="{{ route('tags.store') }}" method="POST" class="flex items-end space-x-4">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori Tag Baru</label>
                        <input type="text" name="nama" required placeholder="Contoh: Makanan Ringan"
                            class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow transition">
                        + Tambah
                    </button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="py-3 px-4 font-semibold text-sm text-gray-600">Nama Tag</th>
                            <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium text-gray-800">{{ $tag->nama }}</td>
                                <td class="py-3 px-4 text-right space-x-2">
                                    <button
                                        @click="
                                        editNama = '{{ addslashes($tag->nama) }}';
                                        editUrl = '{{ route('tags.update', $tag->id) }}';
                                        showEdit = true;
                                    "
                                        class="text-blue-600 hover:text-blue-800 font-medium text-sm">Edit</button>

                                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Hapus Tag ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 font-medium text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-6 text-center text-gray-400">Belum ada Kategori/Tag.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="showEdit" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEdit" @click="showEdit = false"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="showEdit"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form x-bind:action="editUrl" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Tag</h3>
                            <input type="text" name="nama" x-model="editNama" required
                                class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end space-x-2">
                            <button type="button" @click="showEdit = false"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md text-gray-700">Batal</button>
                            <button type="submit"
                                class="bg-blue-600 py-2 px-4 rounded-md text-white hover:bg-blue-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
