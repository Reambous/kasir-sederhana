<div class="space-y-6" x-data="{ showEdit: false, editUrl: '', editNama: '' }">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-sm">
            Nama Tag tidak boleh kosong atau duplikat.
        </div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100">
        <form action="{{ route('tags.store') }}" method="POST"
            class="flex flex-col sm:flex-row items-end space-y-4 sm:space-y-0 sm:space-x-4">
            @csrf
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori Tag Baru</label>
                <input type="text" name="nama" required placeholder="Contoh: Makanan Ringan"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <button type="submit"
                class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow transition">
                + Tambah
            </button>
        </form>
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100">

        <div class="mb-4 flex justify-end">
            <div class="relative w-full sm:w-1/2 md:w-1/3">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama tag..."
                    class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <div wire:loading wire:target="search" class="absolute right-3 top-2.5">
                    <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto relative">
            <div wire:loading.class="opacity-50" class="transition-opacity duration-200">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="py-3 px-4 font-semibold text-sm text-gray-600">Nama Tag</th>
                            <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-3 px-4 font-medium text-gray-800">{{ $tag->nama }}</td>
                                <td class="py-3 px-4 text-right space-x-2">
                                    <button
                                        @click="
                                        editNama = '{{ addslashes($tag->nama) }}';
                                        editUrl = '{{ route('tags.update', $tag->id) }}';
                                        showEdit = true;
                                    "
                                        class="text-blue-600 hover:text-blue-800 font-medium text-sm px-2">Edit</button>

                                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Hapus Tag ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 font-medium text-sm px-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-6 text-center text-gray-400">
                                    {{ $search ? 'Kategori tidak ditemukan.' : 'Belum ada Kategori/Tag.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 border-t border-gray-100 pt-4">
            {{ $tags->links() }}
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
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end space-x-2">
                        <button type="button" @click="showEdit = false"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 py-2 px-4 border border-transparent rounded-md text-white hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
