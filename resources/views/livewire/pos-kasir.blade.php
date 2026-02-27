<div class="w-full flex flex-col lg:flex-row gap-1 bg-slate-200">

    <div class="w-full lg:w-[75%] bg-white border border-slate-400 flex flex-col h-[calc(100vh-6rem)]">

        <div
            class="p-2 bg-slate-900 flex flex-col sm:flex-row justify-between items-center gap-2 border-b-2 border-indigo-600">
            <h3 class="text-sm font-black text-white uppercase tracking-tighter">KATALOG PRODUK</h3>
            <div class="w-full sm:w-1/2 relative">
                <input type="text" wire:model.live="search" placeholder="SCAN BARCODE / KETIK NAMA..."
                    class="w-full pl-3 pr-8 py-1 rounded-none border-0 focus:ring-2 focus:ring-indigo-500 text-xs font-bold uppercase text-slate-900 bg-slate-100 font-mono">
            </div>
        </div>

        <div x-data="{ showTags: false }" class="px-2 py-1.5 border-b border-slate-300 bg-slate-100 shadow-inner">
            <div class="flex items-center gap-2">
                <button @click="showTags = !showTags" type="button"
                    class="text-[10px] font-black text-white bg-slate-700 hover:bg-slate-800 px-3 py-1 uppercase tracking-widest border border-slate-900 rounded-none transition-none shadow-sm">
                    [+] PILIH KATEGORI
                </button>

                @if (!empty($selectedTags))
                    <span
                        class="text-[9px] font-black text-white bg-indigo-600 px-2 py-0.5 uppercase rounded-none border border-indigo-800">
                        {{ count($selectedTags) }} FILTER AKTIF
                    </span>
                    <button wire:click="$set('selectedTags', [])"
                        class="text-[9px] text-rose-600 font-black uppercase hover:underline">BERSIHKAN</button>
                @endif
            </div>

            <div x-show="showTags" x-transition
                class="mt-2 p-2 bg-white border border-slate-400 flex flex-wrap gap-1 shadow-md">
                @foreach ($allTags as $tag)
                    <label
                        class="inline-flex items-center bg-slate-50 border border-slate-300 px-2 py-1 cursor-pointer hover:bg-indigo-600 hover:text-white transition-none rounded-none group">
                        <input type="checkbox" wire:model.live="selectedTags" value="{{ $tag->id }}"
                            class="rounded-none border-slate-500 text-indigo-600 focus:ring-0 h-3 w-3">
                        <span
                            class="ml-1 text-[10px] font-black uppercase group-hover:text-white">{{ $tag->nama }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-2 bg-slate-200/50">
            <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2">
                @forelse($products as $product)
                    <button wire:click="addToCart('{{ $product->id }}')"
                        class="flex flex-col text-left bg-white border-2 border-slate-300 hover:border-indigo-600 transition-all focus:outline-none rounded-none h-40 relative shadow-sm group">

                        <div
                            class="w-full h-20 bg-slate-100 flex items-center justify-center overflow-hidden border-b border-slate-200">
                            @if ($product->gambar)
                                <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}"
                                    class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            @endif
                            <div
                                class="absolute top-0 right-0 bg-slate-900 text-white text-[9px] font-black px-1.5 py-0.5 border-l border-b border-slate-700">
                                {{ $product->jumlah }}
                            </div>
                        </div>

                        <div class="p-2 flex flex-col flex-1 w-full justify-between">
                            <span
                                class="font-black text-slate-900 text-[14px] leading-[1.1] line-clamp-2 uppercase tracking-tight group-hover:text-indigo-700">
                                {{ $product->nama }}
                            </span>
                            <span
                                class="font-black text-indigo-700 text-[11px] bg-indigo-50 px-1 py-0.5 w-fit border border-indigo-100 mt-1">
                                Rp{{ number_format($product->harga_jual, 0, ',', '.') }}
                            </span>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full text-center py-10 text-slate-500 text-xs font-black uppercase">BARANG
                        TIDAK ADA</div>
                @endforelse
            </div>
        </div>
        <div class="p-1 bg-white border-t border-slate-300">
            {{ $products->links() }}
        </div>
    </div>

    <div
        class="w-full lg:w-[25%] bg-white border border-slate-400 flex flex-col h-[calc(100vh-6rem)] shadow-lg rounded-none">

        <div class="p-2 bg-slate-900 text-white border-b-2 border-indigo-600">
            <h3 class="text-xs font-black uppercase tracking-widest">STRUK ANTRIAN</h3>
        </div>
        <div class="px-2 pt-2">
            {{-- NOTIFIKASI SUKSES --}}
            @if (session()->has('success'))
                <div
                    class="p-2 bg-emerald-600 text-white border-2 border-emerald-900 mb-2 flex justify-between items-center shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                    <div class="flex flex-col">
                        <span class="text-[11px] font-black uppercase tracking-widest">✅ BERHASIL</span>
                        <span class="text-[9px] font-bold uppercase">{{ session('success') }}</span>
                    </div>
                    @if (isset($lastOrderId))
                        <a href="{{ route('orders.export', $lastOrderId) }}" target="_blank"
                            class="bg-white text-emerald-900 px-3 py-1 font-black text-[10px] hover:bg-slate-100 border-2 border-emerald-900 transition-none">
                            PRINT STRUK
                        </a>
                    @endif
                </div>
            @endif

            {{-- NOTIFIKASI ERROR / BATAL / STOK KURANG --}}
            @if (session()->has('error'))
                <div
                    class="p-2 bg-rose-600 text-white border-2 border-rose-900 mb-2 shadow-[4px_4px_0px_rgba(0,0,0,1)] animate-pulse">
                    <div class="flex flex-col">
                        <span class="text-[11px] font-black uppercase tracking-widest">⚠️ PERINGATAN!</span>
                        <span class="text-[10px] font-bold uppercase">{{ session('error') }}</span>
                    </div>
                </div>
            @endif
        </div>
        <div class="flex-1 overflow-y-auto p-1 bg-white border-b border-slate-300 font-mono">
            <table class="w-full text-left border-collapse">
                <tbody class="divide-y divide-slate-200">
                    @forelse($cart as $id => $item)
                        <tr class="hover:bg-slate-50 border-b border-dashed border-slate-200">
                            <td class="py-2 pr-1 w-full">
                                <div class="font-black text-slate-900 text-[14px] uppercase leading-none">
                                    {{ $item['nama'] }}</div>
                                <div class="text-[11px] text-slate-500 font-bold mt-1">@
                                    Rp{{ number_format($item['harga_jual'], 0, ',', '.') }}</div>
                            </td>
                            <td class="py-2 whitespace-nowrap">
                                <div
                                    class="flex items-center border-1 border-slate-900 bg-white shadow-[1px_1px_1px_1px_rgba(1,1,1,1)]">
                                    <button wire:click="decreaseQty('{{ $id }}')"
                                        class="w-5 h-6 flex items-center justify-center bg-slate-100 text-slate-900 font-black text-[12px] hover:bg-slate-200 border-r border-slate-900">-</button>

                                    <input type="number"
                                        wire:key="qty-input-{{ $id }}-{{ $item['jumlah'] }}"
                                        wire:change="updateQty('{{ $id }}', $event.target.value)"
                                        value="{{ $item['jumlah'] }}" min="1"
                                        class="w-8 h-6 text-center text-[12px] font-black border-0 p-0 focus:ring-0 bg-white text-slate-900">

                                    <button wire:click="addToCart('{{ $id }}')"
                                        class="w-5 h-6 flex items-center justify-center bg-slate-100 text-slate-900 font-black text-[12px] hover:bg-slate-200 border-l border-slate-900">+</button>
                                </div>
                            </td>
                            <td class="py-2 pl-1 text-right">
                                <button wire:click="removeItem('{{ $id }}')"
                                    class="text-rose-600 font-black text-sm hover:bg-rose-50 px-1">&times;</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-slate-400 py-10 text-[9px] font-black uppercase">
                                BELUM ADA ITEM</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-2 bg-slate-50 space-y-2 border-t border-slate-300">
            <div class="space-y-1">
                <input type="text" wire:model="nama_buyer"
                    class="w-full text-[10px] font-bold uppercase rounded-none border border-slate-400 py-1 px-2 focus:ring-0 focus:border-indigo-600 placeholder-slate-400"
                    placeholder="NAMA PELANGGAN">

                <div class="flex gap-1">
                    <select wire:model.live="metode_pembayaran"
                        class="w-1/2 text-[9px] font-black uppercase rounded-none border border-slate-400 py-1 focus:ring-0">
                        <option value="cash">TUNAI</option>
                        <option value="non_cash">TRANSFER</option>
                    </select>
                    <input type="number" wire:model.live="potongan"
                        class="w-1/2 text-[9px] font-bold uppercase rounded-none border border-slate-400 py-1 px-2 focus:ring-0 placeholder-slate-400"
                        placeholder="DISKON">
                </div>
            </div>

            <div class="flex justify-between items-baseline pt-1 border-t border-slate-300">
                <span class="text-[10px] font-black text-slate-500 uppercase">TOTAL</span>
                <span
                    class="text-2xl font-black text-indigo-700 tracking-tighter leading-none">Rp{{ number_format($this->total, 0, ',', '.') }}</span>
            </div>

            <div x-data="{
                rawUang: $wire.entangle('uang_diterima').live,
                get formattedUang() { return this.rawUang ? new Intl.NumberFormat('id-ID').format(this.rawUang) : ''; },
                updateUang(e) {
                    let v = e.target.value.replace(/\D/g, '');
                    this.rawUang = v ? parseInt(v) : 0;
                    e.target.value = this.rawUang ? new Intl.NumberFormat('id-ID').format(this.rawUang) : '';
                }
            }">
                <div class="relative">
                    <span class="absolute left-2 top-0.5 text-[8px] font-black text-slate-400 uppercase">UANG
                        DITERIMA</span>
                    <input type="text" :value="formattedUang" @input="updateUang"
                        @if ($metode_pembayaran === 'non_cash') readonly @endif
                        class="w-full text-base font-black text-slate-900 text-right rounded-none border-2 border-slate-800 pt-3 pb-1 px-2 focus:ring-0 focus:border-indigo-600 @if ($metode_pembayaran === 'non_cash') bg-slate-200 @else bg-white @endif">
                </div>
            </div>

            <div class="flex gap-1">
                <button wire:click="clearCart" wire:confirm="BATALKAN SEMUA?"
                    class="w-1/4 bg-slate-200 text-slate-800 font-black text-[9px] uppercase rounded-none py-2 border border-slate-400 hover:bg-rose-600 hover:text-white transition-all">BATAL</button>
                <button wire:click="checkout" wire:loading.attr="disabled"
                    class="w-3/4 bg-indigo-600 text-white font-black text-xs uppercase rounded-none py-2 hover:bg-indigo-700 transition-none border border-indigo-900 shadow-[3px_3px_0px_rgba(0,0,0,0.2)]">
                    <span wire:loading.remove wire:target="checkout">PROSES BAYAR</span>
                    <span wire:loading wire:target="checkout">WAIT...</span>
                </button>
            </div>
        </div>
    </div>
</div>
