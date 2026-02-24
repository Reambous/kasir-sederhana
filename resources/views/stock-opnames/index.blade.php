<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stock Opname (Penyesuaian Stok)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">

                @if (!$activeSO)
                    <div class="text-center py-10">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada Stock Opname yang berjalan</h3>
                        <p class="text-gray-500 mb-6">Mulai sesi Stock Opname baru untuk mencocokkan stok fisik dengan
                            sistem.</p>

                        <form action="{{ route('stock-opnames.store') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition">
                                Mulai Stock Opname Baru
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Sesi Aktif: {{ $activeSO->code }}</h3>
                            <p class="text-sm text-gray-500">Dimulai pada: {{ $activeSO->tanggal_mulai }}</p>
                        </div>

                        <div class="flex space-x-3">
                            <form action="{{ route('stock-opnames.cancel', $activeSO->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin membatalkan sesi ini? Semua data input akan hangus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded shadow-sm transition">
                                    Batalkan Sesi
                                </button>
                            </form>

                            <form action="{{ route('stock-opnames.finish', $activeSO->id) }}" method="POST"
                                onsubmit="return confirm('Selesaikan Stock Opname? Stok semua barang akan diperbarui secara permanen sesuai inputan Anda.');">
                                @csrf
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow transition">
                                    Selesaikan & Update Stok
                                </button>
                            </form>
                        </div>
                    </div>

                    @if ($soProducts->isEmpty())
                        <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-gray-600 mb-4">Sesi berhasil dibuat. Silakan sinkronisasi data barang dari
                                gudang.</p>
                            <form action="{{ route('stock-opnames.sync', $activeSO->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                                    Sync Data Barang ke Sini
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('stock-opnames.updateAll', $activeSO->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div
                                class="flex justify-between items-center mb-4 bg-blue-50 p-3 rounded-lg border border-blue-100">
                                <p class="text-sm text-blue-800 font-medium">Ketik angka stok fisik di bawah, lalu tekan
                                    tombol <b>Simpan Semua</b> untuk menyimpan sekaligus.</p>
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow transition">
                                    ✓ Simpan Semua Input
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100 border-b border-gray-200">
                                            <th class="py-3 px-4 font-semibold text-sm text-gray-600">Nama Barang</th>
                                            <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-center">Stok
                                                Sistem</th>
                                            <th class="py-3 px-4 font-semibold text-sm text-gray-600">Input Stok Fisik
                                                Aktual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($soProducts as $item)
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <td class="py-3 px-4 font-medium">
                                                    {{ $item->product->nama ?? 'Barang Dihapus' }}</td>
                                                <td class="py-3 px-4 text-center font-bold text-gray-700">
                                                    {{ $item->jumlah_awal }}</td>
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center space-x-3">
                                                        <input type="number" name="items[{{ $item->id }}]"
                                                            value="{{ $item->jumlah_akhir }}" min="0"
                                                            placeholder="Ketik stok fisik..."
                                                            class="w-32 rounded border-gray-300 py-1.5 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">

                                                        @if (!is_null($item->jumlah_akhir))
                                                            <span
                                                                class="text-xs font-bold text-green-700 bg-green-100 px-2 py-1.5 rounded border border-green-200">
                                                                Tersimpan: {{ $item->jumlah_akhir }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    @endif
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 mt-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Riwayat Stock Opname (Selesai)</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="py-2 px-4">Kode</th>
                                <th class="py-2 px-4">Tanggal Mulai</th>
                                <th class="py-2 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historySO as $history)
                                <tr class="border-b">
                                    <td class="py-2 px-4">{{ $history->code }}</td>
                                    <td class="py-2 px-4">{{ $history->tanggal_mulai }}</td>
                                    <td class="py-2 px-4"><span
                                            class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Selesai</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-gray-400">Belum ada riwayat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
