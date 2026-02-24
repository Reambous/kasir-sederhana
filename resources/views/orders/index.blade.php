<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi (Laporan Penjualan)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Total Pendapatan Keseluruhan</h3>
                    <p class="text-sm text-gray-500">Dari semua transaksi yang tercatat di sistem.</p>
                </div>
                <div class="text-3xl font-black text-green-600">
                    Rp {{ number_format($orders->sum('total_bayar') ?? $orders->sum('uang_diterima'), 0, ',', '.') }}
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">No. Invoice</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Tanggal & Waktu</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Kasir</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Metode</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-right">Total Transaksi
                                </th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 font-bold text-indigo-600">{{ $order->code }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</td>

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
                                            // Menghitung total dari order items secara langsung agar akurat
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
                                            🖨️ Cetak Ulang
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400">Belum ada riwayat
                                        transaksi penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
