<div class="w-full flex flex-col gap-0 min-h-screen bg-slate-50">

    <div class="bg-slate-900 text-white border-b-4 border-indigo-600 p-6 shadow-xl">
        @if (!$activeSO)
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <div class="w-16 h-16 bg-slate-800 flex items-center justify-center border-2 border-slate-700 mb-4">
                    <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-black uppercase tracking-[0.2em] mb-2">Sesi Opname Kosong</h3>
                <p class="text-slate-400 text-xs font-bold uppercase mb-6 tracking-wider">Mulai audit fisik barang untuk
                    menyelaraskan stok sistem.</p>
                <form action="{{ route('stock-opnames.store') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-indigo-600 text-white font-black px-10 py-4 uppercase tracking-widest hover:bg-white hover:text-indigo-700 transition-all border-2 border-indigo-500 shadow-[6px_6px_0px_rgba(255,255,255,0.2)]">
                        + Buka Sesi Baru
                    </button>
                </form>
            </div>
        @else
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                <div>
                    <span
                        class="bg-indigo-600 text-white text-[10px] font-black px-2 py-0.5 uppercase tracking-widest border border-indigo-400 mb-2 inline-block">Sesi
                        Sedang Berjalan</span>
                    <h3 class="text-3xl font-black uppercase tracking-tighter leading-none">KODE: {{ $activeSO->code }}
                    </h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1 tracking-widest">Operator Audit:
                        {{ auth()->user()->nama }} | Mulai:
                        {{ \Carbon\Carbon::parse($activeSO->created_at)->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex gap-2 w-full lg:w-auto">
                    <form action="{{ route('stock-opnames.cancel', $activeSO->id) }}" method="POST"
                        class="flex-1 lg:flex-none"
                        onsubmit="return confirm('BATALKAN SESI? DATA INPUT AKAN HILANG PERMANEN.');">
                        @csrf @method('DELETE')
                        <button
                            class="w-full bg-slate-800 text-white text-xs font-black px-6 py-3 uppercase tracking-widest hover:bg-rose-600 border border-slate-700 transition-none">BATALKAN</button>
                    </form>
                    <form action="{{ route('stock-opnames.finish', $activeSO->id) }}" method="POST"
                        class="flex-1 lg:flex-none"
                        onsubmit="return confirm('SELESAIKAN & UPDATE STOK PERMANEN KE GUDANG?');">
                        @csrf
                        <button
                            class="w-full bg-emerald-600 text-white text-xs font-black px-6 py-3 uppercase tracking-widest border border-emerald-500 shadow-[4px_4px_0px_rgba(0,0,0,0.3)] hover:bg-emerald-500 transition-none">Selesaikan
                            & Sync</button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    @if ($activeSO)
        <div class="w-full flex-1 flex flex-col">
            <div
                class="p-3 bg-slate-100 border-b border-slate-300 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="relative w-full md:w-96">
                    <input type="text" wire:model.live.debounce.300ms="searchActive"
                        placeholder="CARI NAMA BARANG / SCAN BARCODE..."
                        class="w-full pl-10 pr-4 py-2 text-xs font-black border-2 border-slate-300 rounded-none uppercase focus:ring-0 focus:border-indigo-600 bg-white tracking-widest">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-black text-indigo-700 uppercase tracking-widest flex items-center">
                        <span class="relative flex h-2 w-2 mr-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                        </span>
                        Auto-Save Cloud Aktif
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto border-x border-slate-300">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th
                                class="py-3 px-4 font-black text-[10px] uppercase tracking-widest border-r border-slate-700">
                                Identitas Produk</th>
                            <th
                                class="py-3 px-4 font-black text-[10px] uppercase tracking-widest border-r border-slate-700 text-center">
                                Stok Sistem</th>
                            <th
                                class="py-3 px-4 font-black text-[10px] uppercase tracking-widest border-r border-slate-700">
                                Fisik (Input)</th>
                            <th class="py-3 px-4 font-black text-[10px] uppercase tracking-widest">Catatan / Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300 bg-white">
                        @forelse ($activeProducts as $item)
                            <tr class="hover:bg-indigo-50/50 transition-none" wire:key="so-row-{{ $item->id }}">
                                <td class="py-2 px-4 border-r border-slate-200">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-black text-slate-900 text-xs uppercase tracking-tight">{{ $item->product->nama ?? 'PRODUK DIHAPUS' }}</span>
                                        <span class="font-mono text-[9px] text-slate-500 uppercase mt-0.5">BC:
                                            {{ $item->product->barcode ?? '-' }}</span>
                                    </div>
                                </td>

                                <td
                                    class="py-2 px-4 text-center border-r border-slate-200 font-black text-slate-400 text-sm">
                                    {{ $item->jumlah_awal }}
                                </td>

                                <td class="py-2 px-4 border-r border-slate-200 relative group">
                                    <div class="flex items-center gap-2">
                                        <input type="number"
                                            wire:change="updateStok({{ $item->id }}, $event.target.value)"
                                            value="{{ $item->jumlah_akhir }}"
                                            class="w-24 bg-slate-50 border-2 border-slate-800 py-1.5 px-2 text-xs font-black focus:ring-0 focus:bg-white focus:border-indigo-600 rounded-none transition-all"
                                            placeholder="0">

                                        @if (!is_null($item->jumlah_akhir))
                                            <span class="text-emerald-600 font-black text-xs animate-pulse"
                                                title="Tersimpan di Cloud">✓</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-2 px-4 relative">
                                    <div class="flex items-center gap-2">
                                        <input type="text"
                                            wire:change="updateKeterangan({{ $item->id }}, $event.target.value)"
                                            value="{{ $item->keterangan }}" placeholder="Tulis alasan..."
                                            class="w-full bg-transparent border-b-2 border-slate-300 py-1.5 px-1 text-xs font-bold uppercase focus:ring-0 focus:border-indigo-600 rounded-none placeholder-slate-300 transition-all">

                                        @if (!empty($item->keterangan))
                                            <span class="text-emerald-600 font-black text-[10px] uppercase">OK</span>
                                        @endif

                                        <div wire:loading
                                            wire:target="updateKeterangan({{ $item->id }}, $event.target.value)"
                                            class="absolute right-2">
                                            <svg class="animate-spin h-3 w-3 text-indigo-600" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="py-10 text-center text-slate-400 font-black text-xs uppercase">DATA KOSONG
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-slate-50 border-t-2 border-slate-300">
                {{ $activeProducts->links() }}
            </div>
        </div>
    @endif

    <div class="bg-white border-t-4 border-slate-900 p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h3 class="text-sm font-black uppercase tracking-[0.2em] text-slate-900 border-b-2 border-indigo-600 pb-1">
                Arsip Laporan Selesai</h3>
            <div class="relative w-full md:w-80 mt-4 md:mt-0">
                <input type="text" wire:model.live.debounce.300ms="searchHistory"
                    placeholder="CARI KODE LAPORAN..."
                    class="w-full pl-10 py-2 border-2 border-slate-200 text-xs font-bold uppercase focus:ring-0 focus:border-slate-900 rounded-none bg-slate-50">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($historySO as $history)
                <div
                    class="border-2 border-slate-200 bg-white hover:border-indigo-600 transition-colors p-4 relative group">
                    <div class="flex justify-between items-start mb-4">
                        <span
                            class="text-[10px] font-black bg-slate-100 px-2 py-0.5 border border-slate-300 uppercase">Audit
                            Record</span>
                        <span class="text-[10px] font-black text-emerald-600 uppercase">Status: Selesai</span>
                    </div>
                    <h4 class="text-lg font-black text-slate-900 mb-1 tracking-tight">{{ $history->code }}</h4>
                    <p class="text-[10px] font-bold text-slate-500 uppercase mb-4">
                        {{ \Carbon\Carbon::parse($history->created_at)->format('d F Y • H:i') }}</p>

                    <a href="{{ route('stock-opnames.show', $history->id) }}"
                        class="block w-full text-center bg-slate-900 text-white font-black py-2 text-xs uppercase tracking-widest hover:bg-indigo-600 transition-none">
                        Lihat Laporan Lengkap
                    </a>
                </div>
            @empty
                <div class="col-span-full py-10 text-center border-2 border-dashed border-slate-200">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest text-center">Belum ada
                        riwayat laporan</span>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $historySO->links() }}
        </div>
    </div>
</div>
