<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Laporan Stock Opname') }}
            </h2>
            <a href="{{ route('stock-opnames.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded shadow-sm transition text-sm">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-black text-indigo-700">{{ $stockOpname->code }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Tanggal Audit:
                        <b>{{ \Carbon\Carbon::parse($stockOpname->created_at)->format('d F Y, H:i') }}</b></p>
                </div>
                <div class="text-right">
                    <span
                        class="bg-green-100 text-green-800 px-3 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider border border-green-200">
                        Status: Selesai
                    </span>
                    <p class="text-xs text-gray-400 mt-2">Data stok telah diperbarui ke gudang.</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Rincian Hasil Audit Fisik</h4>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Nama Barang</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-center">Stok Sistem (Awal)
                                </th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-center">Stok Fisik (Akhir)
                                </th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-center">Selisih</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-600">Keterangan / Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockOpname->soProducts as $item)
                                @php
                                    // Hitung selisih: Fisik dikurangi Sistem
                                    $selisih = $item->jumlah_akhir - $item->jumlah_awal;
                                @endphp
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 font-medium text-gray-800">
                                        {{ $item->product->nama ?? 'Barang Telah Dihapus' }}</td>
                                    <td class="py-3 px-4 text-center text-gray-500">{{ $item->jumlah_awal }}</td>
                                    <td class="py-3 px-4 text-center font-bold text-gray-800">{{ $item->jumlah_akhir }}
                                    </td>

                                    <td class="py-3 px-4 text-center font-bold">
                                        @if ($selisih < 0)
                                            <span class="text-red-600">{{ $selisih }}</span>
                                        @elseif($selisih > 0)
                                            <span class="text-blue-600">+{{ $selisih }}</span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 text-sm text-gray-600 italic">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400">Tidak ada rincian barang
                                        untuk laporan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
