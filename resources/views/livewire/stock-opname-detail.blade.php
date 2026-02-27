<div class="w-full space-y-4">
    <div
        class="bg-slate-900 text-white border-b-4 border-emerald-500 p-6 flex flex-col md:flex-row justify-between items-center gap-4 shadow-xl">
        <div>
            <div class="flex items-center gap-3">
                <span
                    class="bg-emerald-600 text-white text-[10px] font-black px-2 py-0.5 uppercase tracking-widest border border-emerald-400">Arsip
                    Audit</span>
                <h3 class="text-3xl font-black tracking-tighter uppercase">{{ $stockOpname->code }}</h3>
            </div>
            <p class="text-[11px] text-slate-400 font-bold uppercase mt-2 tracking-wider">
                Tanggal Audit: {{ \Carbon\Carbon::parse($stockOpname->created_at)->format('d F Y, H:i') }} | Status:
                Selesai
            </p>
        </div>
        <div class="text-left md:text-right">
            <button onclick="window.print()"
                class="bg-white text-slate-900 font-black px-6 py-2 text-xs uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-none border-2 border-slate-900 shadow-[4px_4px_0px_rgba(255,255,255,0.2)]">
                🖨️ Cetak Laporan
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-1 bg-slate-300 border border-slate-300 shadow-md">
        <div class="bg-white p-5 border-b-4 border-slate-900">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Produk Di-Audit</p>
            <h4 class="text-3xl font-black text-slate-900">{{ $items->total() }} <span
                    class="text-sm font-bold text-slate-400 uppercase">Item</span></h4>
        </div>
        <div class="bg-white p-5 border-b-4 border-rose-600">
            <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-1">Potensi Kerugian (Stok
                Kurang)</p>
            <h4 class="text-3xl font-black text-rose-600">
                -{{ $items->getCollection()->where('jumlah_akhir', '<', 'jumlah_awal')->count() }}
                <span class="text-sm font-bold uppercase">Produk</span>
            </h4>
        </div>
        <div class="bg-white p-5 border-b-4 border-blue-600">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Stok Berlebih (Kelebihan
                Fisik)</p>
            <h4 class="text-3xl font-black text-blue-600">
                +{{ $items->getCollection()->where('jumlah_akhir', '>', 'jumlah_awal')->count() }}
                <span class="text-sm font-bold uppercase">Produk</span>
            </h4>
        </div>
    </div>

    <div class="bg-white border-2 border-slate-900 shadow-sm overflow-hidden">
        <div
            class="p-3 bg-slate-100 border-b-2 border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4">
            <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Rincian Perbandingan Stok Fisik vs Sistem
            </h4>

            <div class="relative w-full md:w-1/3">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="CARI NAMA BARANG..."
                    class="w-full pl-4 pr-10 py-1.5 rounded-none border-2 border-slate-300 focus:border-indigo-600 focus:ring-0 text-[10px] font-black uppercase bg-white">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest">
                    <tr>
                        <th class="py-3 px-6 border-r border-slate-700">Identitas Barang</th>
                        <th class="py-3 px-6 border-r border-slate-700 text-center">Stok Sistem</th>
                        <th class="py-3 px-6 border-r border-slate-700 text-center">Stok Fisik</th>
                        <th class="py-3 px-6 border-r border-slate-700 text-center">Selisih</th>
                        <th class="py-3 px-4">Keterangan / Justifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse($items as $item)
                        @php $selisih = $item->jumlah_akhir - $item->jumlah_awal; @endphp
                        <tr class="hover:bg-slate-50 transition-none">
                            <td class="py-3 px-6 border-r border-slate-100">
                                <div class="flex flex-col">
                                    <span
                                        class="font-black text-slate-900 text-xs uppercase leading-none">{{ $item->product->nama ?? 'PRODUK DIHAPUS' }}</span>
                                    <span class="font-mono text-[9px] text-slate-500 uppercase mt-1">Barcode:
                                        {{ $item->product->barcode ?? '-' }}</span>
                                </div>
                            </td>
                            <td
                                class="py-3 px-6 text-center border-r border-slate-100 font-bold text-slate-400 text-sm">
                                {{ $item->jumlah_awal }}
                            </td>
                            <td
                                class="py-3 px-6 text-center border-r border-slate-100 font-black text-slate-900 text-sm bg-slate-50/50">
                                {{ $item->jumlah_akhir }}
                            </td>
                            <td class="py-3 px-6 text-center border-r border-slate-100 font-black text-sm uppercase">
                                @if ($selisih < 0)
                                    <span
                                        class="bg-rose-100 text-rose-700 px-2 py-1 border border-rose-300">{{ $selisih }}</span>
                                @elseif($selisih > 0)
                                    <span
                                        class="bg-blue-100 text-blue-700 px-2 py-1 border border-blue-300">+{{ $selisih }}</span>
                                @else
                                    <span class="text-slate-300 font-medium">Sesuai</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-[11px] font-bold text-slate-600 uppercase italic">
                                {{ $item->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="py-12 text-center text-slate-400 font-black text-xs uppercase tracking-widest">
                                Data Audit Kosong</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 bg-slate-50 border-t border-slate-300">
            {{ $items->links() }}
        </div>
    </div>
</div>
