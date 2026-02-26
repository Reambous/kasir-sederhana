<div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

    <div class="md:col-span-2 bg-white rounded-lg shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Daftar Produk</h3>
            <input type="text" wire:model.live="search" placeholder="Cari nama atau barcode..."
                class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div x-data="{ showTags: false }" class="mb-4 pb-3 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <button @click="showTags = !showTags" type="button"
                    class="flex items-center text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg transition border border-gray-200 focus:outline-none shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    Filter Kategori
                    <svg :class="{ 'rotate-180': showTags }" class="w-4 h-4 ml-2 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                @if (!empty($selectedTags))
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-100 px-2 py-1 rounded-full">
                        {{ count($selectedTags) }} Aktif
                    </span>
                    <button wire:click="$set('selectedTags', [])"
                        class="text-xs text-red-500 hover:text-red-700 underline">
                        Reset
                    </button>
                @endif
            </div>

            <div x-show="showTags" x-transition style="display: none;"
                class="mt-3 p-4 bg-gray-50 border border-gray-200 rounded-lg flex flex-wrap gap-2 shadow-inner">
                @foreach ($allTags as $tag)
                    <label
                        class="inline-flex items-center bg-white border border-gray-200 px-3 py-1.5 rounded-full cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition text-sm shadow-sm">
                        <input type="checkbox" wire:model.live="selectedTags" value="{{ $tag->id }}"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-gray-700 font-medium">{{ $tag->nama }}</span>
                    </label>
                @endforeach

                @if ($allTags->isEmpty())
                    <span class="text-sm text-gray-400">Belum ada kategori yang dibuat di menu Gudang.</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            @forelse($products as $product)
                <button wire:click="addToCart('{{ $product->id }}')"
                    class="flex flex-col text-left bg-white p-3 rounded-xl border border-gray-200 hover:border-indigo-400 hover:shadow-md transition-all focus:outline-none group overflow-hidden">
                    <div
                        class="w-full h-32 bg-gray-50 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                        @if ($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        @endif
                    </div>
                    <span
                        class="font-bold text-gray-800 text-sm leading-tight line-clamp-2 w-full">{{ $product->nama }}</span>
                    <span class="text-xs text-gray-500 mt-1">Stok: {{ $product->jumlah }}</span>
                    <span class="font-bold text-indigo-600 mt-auto pt-2">Rp
                        {{ number_format($product->harga_jual, 0, ',', '.') }}</span>
                </button>
            @empty
                <div class="col-span-full text-center py-10 text-gray-500">
                    Produk tidak ditemukan. Coba hapus filter tag atau pencarian.
                </div>
            @endforelse
        </div>

        <div class="pt-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>

    <div
        class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex flex-col sticky top-6 h-[calc(100vh-3rem)]">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Detail Pesanan</h3>

        @if (session()->has('success'))
            <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg border border-green-300">
                <p class="font-bold mb-2">{{ session('success') }}</p>
                @if ($lastOrderId)
                    <a href="{{ route('orders.export', $lastOrderId) }}" target="_blank"
                        class="inline-block bg-white text-green-700 font-bold py-1 px-4 border border-green-500 rounded hover:bg-green-50 transition">
                        🖨️ Cetak Struk Terakhir
                    </a>
                    <button wire:click="$set('lastOrderId', null)"
                        class="ml-2 inline-block text-gray-500 underline text-xs">Tutup</button>
                @endif
            </div>
        @endif
        @if (session()->has('error'))
            <div class="p-3 mb-4 text-sm text-red-800 bg-red-100 rounded-lg border border-red-300">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex-1 overflow-y-auto mb-4 pr-2 space-y-3">
            @forelse($cart as $id => $item)
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded border border-gray-200">
                    <div class="flex-1">
                        <div class="font-semibold text-sm">{{ $item['nama'] }}</div>
                        <div class="text-xs text-gray-500">Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="flex items-center space-x-1">
                        <button wire:click="decreaseQty('{{ $id }}')"
                            class="bg-gray-200 text-gray-700 px-2 py-1 rounded hover:bg-gray-300 transition">-</button>

                        <input type="number" wire:key="qty-{{ $id }}-{{ $item['jumlah'] }}"
                            wire:change="updateQty('{{ $id }}', $event.target.value)"
                            value="{{ $item['jumlah'] }}" min="1"
                            class="w-14 text-center text-sm border-gray-300 rounded py-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <button wire:click="addToCart('{{ $id }}')"
                            class="bg-gray-200 text-gray-700 px-2 py-1 rounded hover:bg-gray-300 transition">+</button>

                        <button wire:click="removeItem('{{ $id }}')"
                            class="ml-2 bg-red-100 text-red-600 p-1.5 rounded hover:bg-red-200 transition"
                            title="Hapus Barang">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400 py-10 text-sm">Keranjang masih kosong</div>
            @endforelse
        </div>

        <div class="border-t pt-4 space-y-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Nama Pembeli (Opsional)</label>
                <input type="text" wire:model="nama_buyer"
                    class="w-full text-sm rounded border-gray-300 py-1.5 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="flex space-x-2">
                <div class="w-1/2">
                    <label class="block text-xs text-gray-500 mb-1">Metode</label>
                    <select wire:model.live="metode_pembayaran"
                        class="w-full text-sm rounded border-gray-300 py-1.5 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="cash">Cash</option>
                        <option value="non_cash">Non Cash</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-xs text-gray-500 mb-1">Potongan (Rp)</label>
                    <input type="number" wire:model.live="potongan"
                        class="w-full text-sm rounded border-gray-300 py-1.5 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="flex justify-between items-center text-lg font-bold text-gray-800 pt-2 border-t">
                <span>Total Bayar:</span>
                <span>Rp {{ number_format($this->total, 0, ',', '.') }}</span>
            </div>

            <div x-data="{
                rawUang: $wire.entangle('uang_diterima').live,
                get formattedUang() {
                    return this.rawUang ? new Intl.NumberFormat('id-ID').format(this.rawUang) : '';
                },
                updateUang(event) {
                    // Hapus semua karakter selain angka
                    let val = event.target.value.replace(/\D/g, '');
                    // Simpan angka murninya ke Livewire
                    this.rawUang = val ? parseInt(val) : 0;
                    // Format ulang tampilan dengan titik
                    event.target.value = this.rawUang ? new Intl.NumberFormat('id-ID').format(this.rawUang) : '';
                }
            }">
                <label class="block text-xs text-gray-500 mb-1">Uang Diterima (Rp)</label>
                <input type="text" :value="formattedUang" @input="updateUang"
                    @if ($metode_pembayaran === 'non_cash') readonly @endif placeholder="Contoh: 100.000"
                    class="w-full text-lg font-bold text-green-600 rounded border-gray-300 py-2 focus:ring-green-500 focus:border-green-500 transition-colors 
                    @if ($metode_pembayaran === 'non_cash') bg-gray-100 cursor-not-allowed @endif">

                @if ($metode_pembayaran === 'non_cash')
                    <p class="text-xs text-indigo-500 mt-1 italic">Nominal otomatis disesuaikan untuk pembayaran
                        Non-Cash.</p>
                @endif
            </div>

            <div class="flex space-x-2 mt-4">

                <button wire:click="clearCart" onclick="return confirm('Yakin ingin membatalkan semua pesanan ini?')"
                    class="w-1/3 bg-red-50 text-red-600 border border-red-200 font-bold py-2 text-sm rounded-lg hover:bg-red-100 transition-all active:scale-95">
                    BATAL
                </button>

                <button wire:click="checkout" wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-wait"
                    class="relative w-2/3 bg-indigo-600 text-white font-bold py-2 text-sm rounded-lg hover:bg-indigo-700 transition-all shadow-md active:scale-95 flex justify-center items-center">

                    <span wire:loading.remove wire:target="checkout">PROSES BAYAR</span>

                    <span wire:loading wire:target="checkout" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Memproses...
                    </span>

                </button>
            </div>
        </div>
    </div>
</div>
