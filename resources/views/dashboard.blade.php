<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-slate-900 uppercase tracking-wider">
            {{ __('Control Panel Dashboard') }}
        </h2>
    </x-slot>

    <div class="w-full">
        <div
            class="bg-slate-900 text-white p-8 border-b-4 border-indigo-600 mb-8 flex flex-col md:flex-row justify-between items-center shadow-lg relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 translate-x-1/2 -translate-y-1/2">
            </div>

            <div class="relative z-10 text-center md:text-left">
                <h3 class="text-3xl font-black tracking-tight mb-1 uppercase">SELAMAT DATANG, {{ auth()->user()->nama }}
                </h3>
                <p class="text-slate-400 font-medium tracking-wide text-sm mt-2">
                    STATUS OTORITAS:
                    <span
                        class="bg-indigo-600 text-white px-3 py-1 text-xs font-black uppercase tracking-widest ml-2 border border-indigo-400 shadow-sm">
                        {{ auth()->user()->role }}
                    </span>
                </p>
            </div>
            <div
                class="relative z-10 mt-6 md:mt-0 text-center md:text-right border-t md:border-t-0 border-slate-700 pt-4 md:pt-0">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Waktu Sistem Server</div>
                <div class="text-2xl font-black text-slate-100 tracking-wider">{{ now()->format('d M Y') }} <span
                        class="text-indigo-400">•</span> {{ now()->format('H:i') }} WIB</div>
            </div>
        </div>

        @if (isset($totalOrders) && isset($totalProducts) && isset($lowStock))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <div
                    class="bg-white border-2 border-slate-200 p-6 shadow-sm hover:border-emerald-500 transition-colors group relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform">
                        <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Transaksi Hari
                                Ini</p>
                            <h4
                                class="text-5xl font-black text-slate-900 group-hover:text-emerald-600 transition-colors">
                                {{ $totalOrders }}</h4>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-600 border border-emerald-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white border-2 border-slate-200 p-6 shadow-sm hover:border-blue-500 transition-colors group relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform">
                        <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Total Item Gudang
                            </p>
                            <h4 class="text-5xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">
                                {{ $totalProducts }}</h4>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 border border-blue-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white border-2 {{ $lowStock > 0 ? 'border-rose-400 bg-rose-50' : 'border-slate-200' }} p-6 shadow-sm hover:border-rose-600 transition-colors group relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform">
                        <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p
                                class="text-xs font-bold {{ $lowStock > 0 ? 'text-rose-600' : 'text-slate-500' }} uppercase tracking-widest mb-1">
                                Peringatan Stok < 10</p>
                                    <h4
                                        class="text-5xl font-black {{ $lowStock > 0 ? 'text-rose-700' : 'text-slate-900' }} group-hover:text-rose-600 transition-colors">
                                        {{ $lowStock }}</h4>
                        </div>
                        <div
                            class="p-3 {{ $lowStock > 0 ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white border-2 border-slate-200 shadow-sm p-6">
            <h3
                class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4 border-b-2 border-slate-100 pb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Jalur Akses Cepat
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kasir')
                    <a href="{{ route('pos') }}"
                        class="flex flex-col items-center justify-center p-6 bg-slate-50 border-2 border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-all group">
                        <svg class="w-12 h-12 mb-4 text-slate-400 group-hover:text-emerald-500 group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="font-black uppercase tracking-wider text-xs">Buka Mesin POS</span>
                    </a>

                    <a href="{{ route('orders.index') }}"
                        class="flex flex-col items-center justify-center p-6 bg-slate-50 border-2 border-slate-200 hover:border-indigo-500 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 transition-all group">
                        <svg class="w-12 h-12 mb-4 text-slate-400 group-hover:text-indigo-500 group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                        <span class="font-black uppercase tracking-wider text-xs">Riwayat Transaksi</span>
                    </a>
                @endif

                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'gudang')
                    <a href="{{ route('products.index') }}"
                        class="flex flex-col items-center justify-center p-6 bg-slate-50 border-2 border-slate-200 hover:border-blue-500 hover:bg-blue-50 text-slate-700 hover:text-blue-700 transition-all group">
                        <svg class="w-12 h-12 mb-4 text-slate-400 group-hover:text-blue-500 group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="font-black uppercase tracking-wider text-xs">Inventaris Gudang</span>
                    </a>

                    <a href="{{ route('stock-opnames.index') }}"
                        class="flex flex-col items-center justify-center p-6 bg-slate-50 border-2 border-slate-200 hover:border-amber-500 hover:bg-amber-50 text-slate-700 hover:text-amber-700 transition-all group">
                        <svg class="w-12 h-12 mb-4 text-slate-400 group-hover:text-amber-500 group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        <span class="font-black uppercase tracking-wider text-xs">Mulai Stock Opname</span>
                    </a>
                @endif

            </div>
        </div>
        <div class="w-full space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 bg-slate-300 border-2 border-slate-900">
                <div class="bg-white p-6 border-r-2 border-slate-900 md:border-b-0 border-b-2">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Transaksi</p>
                    <h4 class="text-4xl font-black text-slate-900 leading-none">
                        {{ \App\Models\Order::count() }}
                    </h4>
                </div>
                <div class="bg-white p-6 border-r-2 border-slate-900 md:border-b-0 border-b-2">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Produk</p>
                    <h4 class="text-4xl font-black text-slate-900 leading-none">
                        {{ \App\Models\Product::count() }}
                    </h4>
                </div>
                <div class="bg-white p-6">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Sesi Opname</p>
                    <h4 class="text-4xl font-black text-slate-900 leading-none">
                        {{ \App\Models\StockOpname::where('status', 'done')->count() }}
                    </h4>
                </div>
            </div>

            <div class="bg-white border-2 border-slate-900 rounded-none overflow-hidden">
                <div class="p-4 bg-slate-900 border-b-2 border-indigo-600 flex justify-between items-center">
                    <h3 class="text-xs font-black text-white uppercase tracking-[0.2em]">Log Transaksi Terbaru</h3>

                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead class="bg-slate-100 border-b-2 border-slate-900">
                            <tr>
                                <th
                                    class="py-3 px-6 text-[10px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">
                                    Invoice</th>
                                <th
                                    class="py-3 px-6 text-[10px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">
                                    Kasir</th>
                                <th
                                    class="py-3 px-6 text-[10px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">
                                    Waktu</th>
                                <th
                                    class="py-3 px-6 text-[10px] font-black text-slate-600 uppercase tracking-widest text-right">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($recentOrders as $order)
                                {{-- Update bagian <tr> agar lebih kaku --}}
                                <tr class="hover:bg-slate-500 hover:text-white group transition-none">
                                    <td
                                        class="py-3 px-6 text-xs font-black text-indigo-600 group-hover:text-indigo-400 border-r border-slate-200 uppercase">
                                        {{ $order->code }}
                                    </td>
                                    <td
                                        class="py-3 px-6 text-xs font-bold text-slate-800 group-hover:text-white uppercase border-r border-slate-200">
                                        {{ $order->user->nama ?? 'Sistem' }}
                                    </td>
                                    <td
                                        class="py-3 px-6 text-[11px] font-bold text-slate-400 border-r border-slate-200">
                                        {{ $order->created_at->format('H:i') }}
                                    </td>
                                    <td class="py-3 px-6 text-xs font-black text-slate-900 text-right">
                                        @php
                                            // Hitung total dari semua item di dalam order ini
                                            $totalBelanja = $order->items->sum(function ($item) {
                                                return $item->harga_jual * $item->jumlah;
                                            });

                                            // Kurangi dengan potongan yang ada di model Order
                                            $totalAkhir = $totalBelanja - ($order->potongan ?? 0);
                                        @endphp

                                        Rp{{ number_format($totalAkhir, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="py-12 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Belum ada data masuk</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
