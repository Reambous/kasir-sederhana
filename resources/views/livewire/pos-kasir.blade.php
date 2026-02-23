<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="md:col-span-2 bg-white rounded-lg shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Daftar Produk</h3>
            <input type="text" wire:model.live="search" placeholder="Cari nama atau barcode..."
                class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 h-[600px] overflow-y-auto pr-2">
            @forelse($products as $product)
                <button wire:click="addToCart('{{ $product->id }}')"
                    class="flex flex-col text-left bg-gray-50 p-4 rounded-lg border border-gray-200 hover:bg-indigo-50 hover:border-indigo-300 transition-colors focus:outline-none">
                    <span class="font-semibold text-gray-800 truncate w-full">{{ $product->nama }}</span>
                    <span class="text-sm text-gray-500 mb-2">Stok: {{ $product->jumlah }}</span>
                    <span class="font-bold text-indigo-600 mt-auto">Rp
                        {{ number_format($product->harga_jual, 0, ',', '.') }}</span>
                </button>
            @empty
                <div class="col-span-full text-center py-10 text-gray-500">
                    Produk tidak ditemukan atau belum ada data.
                </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex flex-col h-[680px]">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Detail Pesanan</h3>

        @if (session()->has('success'))
            <div class="p-3 mb-4 text-sm text-green-800 bg-green-100 rounded-lg">{{ session('success') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="p-3 mb-4 text-sm text-red-800 bg-red-100 rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="flex-1 overflow-y-auto mb-4 pr-2 space-y-3">
            @forelse($cart as $id => $item)
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded border border-gray-100">
                    <div class="flex-1">
                        <div class="font-semibold text-sm">{{ $item['nama'] }}</div>
                        <div class="text-xs text-gray-500">Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button wire:click="decreaseQty('{{ $id }}')"
                            class="bg-red-100 text-red-600 px-2 py-1 rounded hover:bg-red-200">-</button>
                        <span class="font-bold text-sm w-6 text-center">{{ $item['jumlah'] }}</span>
                        <button wire:click="addToCart('{{ $id }}')"
                            class="bg-green-100 text-green-600 px-2 py-1 rounded hover:bg-green-200">+</button>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400 py-10 text-sm">Keranjang masih kosong</div>
            @endforelse
        </div>

        <div class="border-t pt-4 space-y-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Nama Pembeli (Opsional)</label>
                <input type="text" wire:model="nama_buyer" class="w-full text-sm rounded border-gray-300 py-1.5">
            </div>

            <div class="flex space-x-2">
                <div class="w-1/2">
                    <label class="block text-xs text-gray-500 mb-1">Metode</label>
                    <select wire:model="metode_pembayaran" class="w-full text-sm rounded border-gray-300 py-1.5">
                        <option value="cash">Cash</option>
                        <option value="non_cash">Non Cash</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-xs text-gray-500 mb-1">Potongan (Rp)</label>
                    <input type="number" wire:model.live="potongan"
                        class="w-full text-sm rounded border-gray-300 py-1.5">
                </div>
            </div>

            <div class="flex justify-between items-center text-lg font-bold text-gray-800 pt-2 border-t">
                <span>Total Bayar:</span>
                <span>Rp {{ number_format($this->total, 0, ',', '.') }}</span>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Uang Diterima (Rp)</label>
                <input type="number" wire:model="uang_diterima"
                    class="w-full text-lg font-bold text-green-600 rounded border-gray-300 py-2">
            </div>

            <button wire:click="checkout"
                class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition-colors mt-2">
                PROSES PEMBAYARAN
            </button>
        </div>
    </div>
</div>
