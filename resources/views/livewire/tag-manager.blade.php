<div class="w-full" x-data="{ showEdit: false, editUrl: '', editNama: '' }">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 bg-white border border-slate-300 shadow-sm p-5 border-t-4 border-indigo-600 h-fit">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-4">Buat Kategori Baru</h3>
            <form action="{{ route('tags.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-1">Nama Kategori</label>
                    <input type="text" name="nama" required placeholder="MISAL: SNACK"
                        class="w-full border-2 border-slate-300 rounded-none text-sm font-bold uppercase focus:border-indigo-600 focus:ring-0">
                </div>
                <button type="submit"
                    class="w-full bg-slate-900 text-white font-black py-3 text-xs uppercase tracking-widest hover:bg-indigo-600 transition-none rounded-none shadow-md">
                    + TAMBAH KATEGORI
                </button>
            </form>
        </div>

        <div class="md:col-span-2 bg-white border border-slate-300 shadow-sm overflow-hidden">
            <div class="p-3 bg-slate-100 border-b border-slate-300 flex justify-between items-center">
                <span class="text-xs font-black uppercase tracking-widest text-slate-600">Daftar Tag</span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="CARI..."
                    class="text-[10px] font-bold border-slate-300 rounded-none py-1 focus:ring-0 uppercase">
            </div>
            <table class="w-full text-left">
                <tbody class="divide-y divide-slate-200">
                    @foreach ($tags as $tag)
                        <tr class="hover:bg-slate-50 transition-none">
                            <td class="py-3 px-4 font-bold text-slate-800 uppercase text-xs tracking-wide">
                                {{ $tag->nama }}</td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <button
                                    @click="editNama = '{{ addslashes($tag->nama) }}'; editUrl = '{{ route('tags.update', $tag->id) }}'; showEdit = true;"
                                    class="text-indigo-600 font-black text-[10px] uppercase hover:underline">EDIT</button>
                                <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('HAPUS?');">
                                    @csrf @method('DELETE')
                                    <button
                                        class="text-rose-600 font-black text-[10px] uppercase hover:underline">HAPUS</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-2 border-t border-slate-200 bg-slate-50">{{ $tags->links() }}</div>
        </div>
    </div>

    <template x-if="showEdit">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/80" @click="showEdit = false"></div>
            <div
                class="relative bg-white border-2 border-slate-900 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-full max-w-sm rounded-none">
                <form :action="editUrl" method="POST" class="p-5">
                    @csrf @method('PUT')
                    <h3 class="text-xs font-black uppercase mb-4 tracking-widest">Update Kategori</h3>
                    <input type="text" name="nama" x-model="editNama" required
                        class="w-full border-2 border-slate-800 text-sm font-bold uppercase rounded-none focus:ring-0">
                    <div class="mt-5 flex gap-2">
                        <button type="button" @click="showEdit = false"
                            class="flex-1 border-2 border-slate-900 font-black text-[10px] uppercase py-2 hover:bg-slate-100">BATAL</button>
                        <button type="submit"
                            class="flex-1 bg-indigo-600 text-white border-2 border-slate-900 font-black text-[10px] uppercase py-2 shadow-[4px_4px_0px_rgba(0,0,0,1)]">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
