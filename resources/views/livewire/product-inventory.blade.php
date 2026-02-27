<div x-data="{ showAdd: false, showEdit: false, editUrl: '', form: { nama: '', barcode: '', harga_beli: '', harga_jual: '', jumlah: '', tags: [] } }" class="w-full">

    @if (session('success'))
        <div
            class="mb-4 bg-emerald-600 text-white px-4 py-3 border-l-8 border-emerald-900 shadow-md uppercase font-black text-xs tracking-widest">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-300 shadow-sm">
        <div
            class="p-4 bg-slate-900 border-b-2 border-indigo-600 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="text-sm font-black text-white uppercase tracking-widest">LOGISTIK INVENTARIS GUDANG</h3>

            <div class="flex w-full md:w-2/3 justify-end gap-2">
                <div class="relative w-full md:w-1/2">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="CARI NAMA / BARCODE..."
                        class="w-full pl-10 bg-slate-800 border-0 text-white text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500 rounded-none py-2">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <button @click="showAdd = true"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-2 px-4 text-xs uppercase tracking-widest border border-indigo-800 transition-none rounded-none shadow-md">
                    + TAMBAH BARANG
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-100 text-slate-600 border-b border-slate-300">
                    <tr>
                        <th class="py-3 px-4 font-black text-[10px] uppercase tracking-tighter">NAMA PRODUK</th>
                        <th class="py-3 px-4 font-black text-[10px] uppercase tracking-tighter">BARCODE</th>
                        <th class="py-3 px-4 font-black text-[10px] uppercase tracking-tighter text-center">STOK</th>
                        <th class="py-3 px-4 font-black text-[10px] uppercase tracking-tighter text-right">HARGA JUAL
                        </th>
                        <th class="py-3 px-4 font-black text-[10px] uppercase tracking-tighter text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50 transition-none" wire:key="prod-{{ $product->id }}">
                            <td class="py-2 px-4 font-bold text-slate-900 text-sm uppercase leading-tight">
                                {{ $product->nama }}</td>
                            <td class="py-2 px-4 font-mono text-xs text-slate-500">{{ $product->barcode }}</td>
                            <td class="py-2 px-4 text-center">
                                <span
                                    class="px-2 py-0.5 font-black text-xs {{ $product->jumlah < 10 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $product->jumlah }}
                                </span>
                            </td>
                            <td class="py-2 px-4 text-right font-black text-indigo-700 text-sm">
                                Rp{{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 text-center space-x-1">
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
                                    class="bg-slate-800 text-white text-[10px] font-black px-2 py-1 uppercase hover:bg-indigo-600 transition-none">EDIT</button>

                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    class="inline" onsubmit="return confirm('HAPUS PERMANEN?');">
                                    @csrf @method('DELETE')
                                    <button
                                        class="bg-rose-600 text-white text-[10px] font-black px-2 py-1 uppercase hover:bg-rose-800 transition-none">HAPUS</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400 text-xs font-black uppercase">
                                GUDANG KOSONG</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-slate-50 border-t border-slate-300">
            {{ $products->links() }}
        </div>
    </div>

    <template x-if="showAdd || showEdit">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="showAdd = false; showEdit = false">
            </div>
            <div
                class="relative bg-white border-2 border-slate-900 shadow-[10px_10px_0px_rgba(0,0,0,1)] w-full max-w-lg rounded-none overflow-hidden">
                <form :action="showAdd ? '{{ route('products.store') }}' : editUrl" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <template x-if="showEdit">@method('PUT')</template>

                    <div class="p-5">
                        <h3 class="text-sm font-black uppercase tracking-widest border-b-2 border-slate-900 pb-2 mb-4"
                            x-text="showAdd ? 'INPUT BARANG BARU' : 'EDIT DATA BARANG'"></h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase">Nama Produk</label>
                                <input type="text" name="nama" x-model="form.nama" required
                                    class="w-full border-2 border-slate-800 rounded-none text-sm font-bold uppercase focus:ring-0 focus:border-indigo-600">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase">Barcode</label>
                                    <input type="text" name="barcode" x-model="form.barcode" required
                                        class="w-full border-2 border-slate-800 rounded-none text-sm font-bold uppercase focus:ring-0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase">Stok
                                        Awal</label>
                                    <input type="number" name="jumlah" x-model="form.jumlah" required
                                        class="w-full border-2 border-slate-800 rounded-none text-sm font-bold uppercase focus:ring-0">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase">Harga
                                        Beli</label>
                                    <input type="number" name="harga_beli" x-model="form.harga_beli" required
                                        class="w-full border-2 border-slate-800 rounded-none text-sm font-bold uppercase focus:ring-0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase">Harga
                                        Jual</label>
                                    <input type="number" name="harga_jual" x-model="form.harga_jual" required
                                        class="w-full border-2 border-slate-800 rounded-none text-sm font-bold uppercase focus:ring-0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-100 p-4 border-t-2 border-slate-900 flex justify-end gap-2">
                        <button type="button" @click="showAdd = false; showEdit = false"
                            class="bg-white border-2 border-slate-900 px-4 py-2 text-xs font-black uppercase hover:bg-slate-200 transition-none rounded-none">BATAL</button>
                        <button type="submit"
                            class="bg-indigo-600 text-white border-2 border-slate-900 px-6 py-2 text-xs font-black uppercase hover:bg-indigo-700 transition-none rounded-none shadow-[4px_4px_0px_rgba(0,0,0,1)]">SIMPAN
                            DATA</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
