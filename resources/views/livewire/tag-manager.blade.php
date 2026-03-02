<div class="w-full" x-data="{ showEdit: false, editUrl: '', editNama: '' }">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border-2 border-slate-900 bg-slate-300">

        <div class="md:col-span-1  p-6 border-b-2 md:border-b-0   border-slate-900 h-fit flex flex-col">
            <h3
                class="text-xs font-black uppercase tracking-widest text-slate-900 mb-5 border-b-2 border-indigo-600 pb-2">
                Buat Kategori Baru</h3>
            <form action="{{ route('tags.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Nama Kategori</label>
                    <input type="text" name="nama" required placeholder="MISAL: SNACK"
                        class="w-full border-2 border-slate-900 rounded-none text-sm font-bold uppercase focus:border-indigo-600 focus:ring-0 bg-slate-50 focus:bg-white">
                </div>
                <button type="submit"
                    class="w-full bg-slate-900 text-white font-black py-3 text-xs uppercase tracking-widest hover:bg-indigo-600 transition-none rounded-none border-2 border-slate-900">
                    + TAMBAH KATEGORI
                </button>
            </form>
        </div>

        <div class="md:col-span-2 bg-white overflow-hidden flex flex-col md:border-l-2 border-slate-900">
            <div class="p-4 bg-slate-900 border-b-2 border-indigo-600 flex justify-between items-center">
                <span class="text-xs font-black uppercase tracking-widest text-white">Daftar Tag</span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="CARI..."
                    class="text-[10px] font-black border-0 bg-slate-800 text-white rounded-none py-1.5 px-3 focus:ring-2 focus:ring-indigo-500 uppercase w-32 md:w-48 placeholder-slate-500">
            </div>

            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($tags as $tag)
                            <tr class="hover:bg-indigo-50 transition-none group">
                                <td
                                    class="py-3 px-6 font-black text-slate-800 uppercase text-xs tracking-wide group-hover:text-indigo-700">
                                    {{ $tag->nama }}
                                </td>
                                <td class="py-3 px-6 text-right space-x-1">
                                    <button
                                        @click="editNama = '{{ addslashes($tag->nama) }}'; editUrl = '{{ route('tags.update', $tag->id) }}'; showEdit = true;"
                                        class="bg-slate-800 text-white font-black text-[10px] uppercase px-3 py-1.5 hover:bg-indigo-600 transition-none border border-slate-800 inline-block">EDIT</button>

                                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('HAPUS KATEGORI INI?');">
                                        @csrf @method('DELETE')
                                        <button
                                            class="bg-rose-600 text-white font-black text-[10px] uppercase px-3 py-1.5 hover:bg-rose-800 transition-none border border-rose-600 inline-block">HAPUS</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2"
                                    class="py-12 text-center font-black text-xs text-slate-400 uppercase tracking-widest">
                                    Belum ada Kategori</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-t-2 border-slate-900 bg-slate-50">{{ $tags->links() }}</div>
        </div>
    </div>

    <template x-if="showEdit">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/90" @click="showEdit = false"></div>
            <div class="relative bg-white border-4 border-slate-900 w-full max-w-sm rounded-none">
                <form :action="editUrl" method="POST" class="p-6">
                    @csrf @method('PUT')
                    <h3
                        class="text-xs font-black text-slate-900 uppercase mb-5 tracking-widest border-b-2 border-indigo-600 pb-2">
                        Update Kategori</h3>
                    <input type="text" name="nama" x-model="editNama" required
                        class="w-full border-2 border-slate-900 text-sm font-bold uppercase rounded-none focus:ring-0 focus:bg-indigo-50 bg-slate-50 mb-6">

                    <div class="flex gap-0 border-2 border-slate-900">
                        <button type="button" @click="showEdit = false"
                            class="flex-1 bg-white font-black text-[10px] text-slate-900 uppercase py-3 hover:bg-slate-200 border-r-2 border-slate-900 transition-none">BATAL</button>
                        <button type="submit"
                            class="flex-1 bg-indigo-600 text-white font-black text-[10px] uppercase py-3 hover:bg-indigo-700 transition-none">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
