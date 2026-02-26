<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Total Pendapatan</h3>
            <p class="text-sm text-gray-500">
                @if ($search)
                    Pencarian: <b class="text-indigo-600">"{{ $search }}"</b>
                @else
                    Dari keseluruhan transaksi.
                @endif
            </p>
        </div>
        <div class="text-3xl font-black text-green-600">
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">

        <div class="mb-4 flex justify-end">
            <div class="flex w-full md:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Ketik No. Invoice atau Kasir..."
                    class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">

                @if ($search)
                    <button wire:click="$set('search', '')"
                        class="ml-2 bg-gray-100 text-gray-600 px-4 py-2 rounded-md hover:bg-red-100 hover:text-red-600 transition font-semibold text-sm">
                        &times;
                    </button>
                @endif

                <div wire:loading wire:target="search" class="absolute right-12 top-2.5">
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
                            <th class="py-3 px-4 font-semibold text-sm text-gray-600">No. Invoice</th>
                            <th class="py-3 px-4 font-semibold text-sm text-gray-600">Tanggal & Waktu</th>
                            <th class="py-3 px-4 font-semibold text-sm text-gray-600">Kasir</th>
                            <th class="py-3 px-4 font-semibold text-sm text-gray-600">Metode</th>
                            <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-right">Total Transaksi</th>
                            <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-3 px-4 font-bold text-indigo-600">{{ $order->code }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3 px-4 text-sm font-medium text-gray-800">
                                    {{ $order->user->nama ?? 'Sistem' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if ($order->metode_pembayaran == 'cash')
                                        <span
                                            class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">CASH</span>
                                    @else
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded">NON-CASH</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-gray-800">
                                    @php
                                        $totalBelanja = $order->items->sum(function ($item) {
                                            return $item->harga_jual * $item->jumlah;
                                        });
                                        $totalAkhir = $totalBelanja - $order->potongan;
                                    @endphp
                                    Rp {{ number_format($totalAkhir, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('orders.export', $order->id) }}" target="_blank"
                                        class="inline-block bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 hover:text-indigo-600 font-semibold py-1 px-3 rounded text-sm transition">
                                        🖨️ Cetak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">
                                    {{ $search ? 'Data tidak ditemukan.' : 'Belum ada riwayat transaksi penjualan.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 border-t border-gray-100 pt-4">
            {{ $orders->links() }}
        </div>

    </div>
</div>
