<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
        @if (!$activeSO)
            <div class="text-center py-10">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada Stock Opname yang berjalan</h3>
                <p class="text-gray-500 mb-6">Mulai sesi Stock Opname baru untuk mencocokkan stok fisik dengan sistem.
                </p>
                <form action="{{ route('stock-opnames.store') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition">
                        Mulai Stock Opname Baru
                    </button>
                </form>
            </div>
        @else
            <div
                class="flex flex-col md:flex-row justify-between md:items-center mb-6 border-b pb-4 space-y-4 md:space-y-0">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Sesi Aktif: {{ $activeSO->code }}</h3>
                    <p class="text-sm text-gray-500">Dimulai pada:
                        {{ \Carbon\Carbon::parse($activeSO->created_at)->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex space-x-3">
                    <form action="{{ route('stock-opnames.cancel', $activeSO->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin membatalkan sesi ini? Semua data input akan hangus.');">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded shadow-sm transition">Batalkan
                            Sesi</button>
                    </form>
                    <form action="{{ route('stock-opnames.finish', $activeSO->id) }}" method="POST"
                        onsubmit="return confirm('Selesaikan Stock Opname? Stok semua barang akan diperbarui secara permanen sesuai inputan Anda.');">
                        @csrf
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow transition">Selesaikan
                            & Update Stok</button>
                    </form>
                </div>
            </div>

            @if ($activeProducts->isEmpty() && !$searchActive)
                <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <p class="text-gray-600 mb-4">Sesi berhasil dibuat. Silakan sinkronisasi data barang dari gudang.
                    </p>
                    <form action="{{ route('stock-opnames.sync', $activeSO->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">Sync
                            Data Barang ke Sini</button>
                    </form>
                </div>
            @else
                <div
                    class="flex flex-col md:flex-row justify-between items-center mb-4 bg-blue-50 p-4 rounded-lg border border-blue-100 space-y-3 md:space-y-0">

                    <div class="relative w-full md:w-1/3">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="searchActive"
                            placeholder="Cari barang (Server-side)..."
                            class="w-full pl-9 rounded border-blue-200 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    </div>

                    <div class="flex items-center space-x-3 w-full md:w-auto justify-end">
                        <span class="text-sm text-blue-800 font-medium hidden lg:block mr-2">
                            <span class="animate-pulse inline-block h-2 w-2 bg-green-500 rounded-full mr-1"></span>
                            Auto-Save Aktif: Ketik angka dan otomatis tersimpan.
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-lg relative">
                    <div wire:loading.class="opacity-50" class="transition-opacity duration-200">
                        <table class="w-full text-left border-collapse relative">
                            <thead class="bg-gray-100 shadow-sm">
                                <tr class="border-b border-gray-200">
                                    <th class="py-3 px-4 font-semibold text-sm text-gray-600">Nama Barang</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-center">Sistem</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-gray-600">Fisik (Input)</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-gray-600">Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activeProducts as $item)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors"
                                        wire:key="item-row-{{ $item->id }}">

                                        <td class="py-3 px-4">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-800">
                                                    {{ $item->product->nama ?? 'Barang Dihapus' }}
                                                </span>
                                                <span
                                                    class="text-xs font-mono text-gray-500 bg-gray-100 px-1 py-0.5 rounded w-fit mt-1 border border-gray-200">
                                                    {{ $item->product->barcode ?? '-' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold text-gray-500">
                                            {{ $item->jumlah_awal }}</td>

                                        <td class="py-3 px-4 relative">
                                            <div class="flex items-center space-x-2">
                                                <input type="number" wire:key="input-qty-{{ $item->id }}"
                                                    wire:change="updateStok({{ $item->id }}, $event.target.value)"
                                                    value="{{ $item->jumlah_akhir }}" min="0"
                                                    placeholder="Stok..."
                                                    class="w-20 md:w-28 rounded border-gray-300 py-1.5 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">

                                                @if (!is_null($item->jumlah_akhir))
                                                    <span
                                                        class="text-xs font-bold text-green-700 bg-green-100 px-2 py-1 rounded border border-green-200">✓</span>
                                                @endif

                                                <div wire:loading wire:target="updateStok({{ $item->id }})"
                                                    class="absolute right-2 text-indigo-500">
                                                    <svg class="animate-spin h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="py-3 px-4 relative">
                                            <input type="text" wire:key="input-ket-{{ $item->id }}"
                                                wire:change="updateKeterangan({{ $item->id }}, $event.target.value)"
                                                value="{{ $item->keterangan }}" placeholder="Catatan..."
                                                class="w-full rounded border-gray-300 py-1.5 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-gray-700">

                                            <div wire:loading wire:target="updateKeterangan({{ $item->id }})"
                                                class="absolute right-6 top-5 text-indigo-500">
                                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-400">
                                            {{ $searchActive ? 'Barang tidak ditemukan.' : 'Tidak ada data barang.' }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $activeProducts->links() }}
                </div>
            @endif
        @endif
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 mt-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-700">Riwayat Laporan (Selesai)</h3>

            <div class="relative w-full md:w-1/3 mt-3 md:mt-0">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="searchHistory"
                    placeholder="Cari Kode Laporan..."
                    class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
        </div>

        <div class="overflow-x-auto relative">
            <div wire:loading.class="opacity-50" class="transition-opacity duration-200">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="py-2 px-4">Kode Laporan</th>
                            <th class="py-2 px-4">Tanggal Audit</th>
                            <th class="py-2 px-4">Status</th>
                            <th class="py-2 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historySO as $history)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 px-4 font-bold text-indigo-600">{{ $history->code }}</td>
                                <td class="py-2 px-4">
                                    {{ \Carbon\Carbon::parse($history->created_at)->format('d M Y, H:i') }}</td>
                                <td class="py-2 px-4"><span
                                        class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Selesai</span>
                                </td>
                                <td class="py-2 px-4 text-center">
                                    <a href="{{ route('stock-opnames.show', $history->id) }}"
                                        class="inline-block bg-white border border-gray-300 text-indigo-600 hover:bg-indigo-50 font-semibold py-1 px-3 rounded text-xs transition">
                                        📄 Lihat Laporan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-400">Tidak ada riwayat ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 border-t border-gray-100 pt-4">
            {{ $historySO->links() }}
        </div>
    </div>
</div>
