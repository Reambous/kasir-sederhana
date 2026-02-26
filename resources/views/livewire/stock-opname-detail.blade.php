<div class="space-y-6">
    <div
        class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex flex-col md:flex-row justify-between md:items-center space-y-4 md:space-y-0">
        <div>
            <h3 class="text-2xl font-black text-indigo-700">{{ $stockOpname->code }}</h3>
            <p class="text-sm text-gray-500 mt-1">Tanggal Audit:
                <b>{{ \Carbon\Carbon::parse($stockOpname->created_at)->format('d F Y, H:i') }}</b></p>
        </div>
        <div class="text-left md:text-right">
            <span
                class="bg-green-100 text-green-800 px-3 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider border border-green-200">
                Status: Selesai
            </span>
            <p class="text-xs text-gray-400 mt-2">Data stok telah diperbarui ke gudang.</p>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 border-b pb-4 space-y-3 md:space-y-0">
            <h4 class="text-lg font-bold text-gray-800">Rincian Hasil Audit Fisik</h4>

            <div class="relative w-full md:w-1/3">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama barang..."
                    class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
        </div>

        <div class="overflow-x-auto relative">
            <div wire:loading.class="opacity-50" class="transition-opacity duration-200">
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
                        @forelse($items as $item)
                            @php $selisih = $item->jumlah_akhir - $item->jumlah_awal; @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-3 px-4 font-medium text-gray-800">
                                    {{ $item->product->nama ?? 'Barang Telah Dihapus' }}</td>
                                <td class="py-3 px-4 text-center text-gray-500">{{ $item->jumlah_awal }}</td>
                                <td class="py-3 px-4 text-center font-bold text-gray-800">{{ $item->jumlah_akhir }}</td>
                                <td class="py-3 px-4 text-center font-bold">
                                    @if ($selisih < 0)
                                        <span class="text-red-600">{{ $selisih }}</span>
                                    @elseif($selisih > 0)
                                        <span class="text-blue-600">+{{ $selisih }}</span>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 italic">{{ $item->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400">Tidak ada data ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 pt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>
