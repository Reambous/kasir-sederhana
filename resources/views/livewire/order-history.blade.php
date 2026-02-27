<div class="w-full">
    <div
        class="bg-slate-900 text-white rounded-none border-l-8 border-indigo-600 p-5 flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div>
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">TOTAL PENDAPATAN</h3>
            <p class="text-[11px] text-slate-300 mt-1 uppercase font-bold">
                @if ($search)
                    PENCARIAN: <span class="text-indigo-400">"{{ $search }}"</span>
                @else
                    KESELURUHAN DATA TRANSAKSI
                @endif
            </p>
        </div>
        <div class="text-3xl font-black text-white tracking-tighter">
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </div>
    </div>

    <div class="bg-white border border-slate-300 rounded-none">

        <div class="p-3 border-b border-slate-300 bg-slate-100 flex justify-end">
            <div class="w-full md:w-1/3 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="CARI NO. INVOICE..."
                    class="w-full pl-3 pr-8 py-1.5 rounded-none border border-slate-400 focus:border-indigo-600 focus:ring-0 text-xs font-bold uppercase text-slate-900 bg-white">
                @if ($search)
                    <button wire:click="$set('search', '')"
                        class="absolute right-2 top-1.5 text-rose-600 hover:text-rose-800 font-black text-sm">&times;</button>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <th
                            class="py-2.5 px-4 font-black text-[10px] uppercase tracking-widest border-r border-slate-700">
                            INVOICE</th>
                        <th
                            class="py-2.5 px-4 font-black text-[10px] uppercase tracking-widest border-r border-slate-700">
                            WAKTU</th>
                        <th
                            class="py-2.5 px-4 font-black text-[10px] uppercase tracking-widest border-r border-slate-700">
                            KASIR</th>
                        <th
                            class="py-2.5 px-4 font-black text-[10px] uppercase tracking-widest border-r border-slate-700">
                            METODE</th>
                        <th
                            class="py-2.5 px-4 font-black text-[10px] uppercase tracking-widest text-right border-r border-slate-700">
                            NILAI</th>
                        <th class="py-2.5 px-4 font-black text-[10px] uppercase tracking-widest text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300 text-slate-800">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-200 transition-none" wire:key="order-{{ $order->id }}">
                            <td class="py-2 px-4 font-black text-indigo-700 text-xs">{{ $order->code }}</td>
                            <td class="py-2 px-4 text-xs font-bold">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</td>
                            <td class="py-2 px-4 text-xs font-bold uppercase">{{ $order->user->nama ?? 'SISTEM' }}</td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-1.5 py-0.5 text-[9px] font-black uppercase border border-slate-800 bg-slate-100 text-slate-900">
                                    {{ $order->metode_pembayaran }}
                                </span>
                            </td>
                            <td class="py-2 px-4 text-right font-black text-xs text-slate-900">
                                @php
                                    $totalBelanja = $order->items->sum(function ($item) {
                                        return $item->harga_jual * $item->jumlah;
                                    });
                                    $totalAkhir = $totalBelanja - $order->potongan;
                                @endphp
                                Rp {{ number_format($totalAkhir, 0, ',', '.') }}
                            </td>
                            <td class="py-2 px-4 text-center space-x-1">
                                <a href="{{ route('orders.export', $order->id) }}" target="_blank"
                                    class="inline-block bg-slate-900 text-white hover:bg-indigo-600 font-black py-1 px-2 text-[10px] uppercase transition-none border border-slate-900 rounded-none">
                                    CETAK
                                </a>
                                <button wire:click="deleteOrder('{{ $order->id }}')" wire:confirm="HAPUS PERMANEN?"
                                    class="inline-block bg-rose-600 text-white hover:bg-rose-800 font-black py-1 px-2 text-[10px] uppercase transition-none border border-rose-800 rounded-none">
                                    HAPUS
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500 text-xs font-black uppercase">
                                {{ $search ? 'TIDAK DITEMUKAN' : 'KOSONG' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t border-slate-300 bg-slate-100 text-xs">
            {{ $orders->links() }}
        </div>
    </div>
</div>
