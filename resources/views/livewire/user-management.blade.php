<div class="w-full">
    <div
        class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b-2 border-slate-900 pb-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Manajemen Pengguna</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">SISTEM KONTROL AKSES ENTERPRISE</p>
        </div>
        <button wire:click="openModal"
            class="bg-slate-900 hover:bg-slate-800 text-white uppercase tracking-widest text-xs font-bold py-3 px-6 rounded-none shadow-md transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
            </svg>
            TAMBAH PENGGUNA
        </button>
    </div>

    @if (session()->has('success'))
        <div
            class="mb-6 p-4 bg-emerald-500 text-white text-sm font-bold tracking-wide shadow-sm rounded-none flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div
            class="mb-6 p-4 bg-rose-600 text-white text-sm font-bold tracking-wide shadow-sm rounded-none flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border-2 border-slate-200 shadow-sm rounded-none">
        <div class="p-4 border-b-2 border-slate-200 bg-slate-50 flex justify-end">
            <div class="relative w-full md:w-96">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="CARI NAMA ATAU EMAIL..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm font-bold bg-white border-2 border-slate-300 rounded-none focus:border-slate-900 focus:ring-0 text-slate-900 placeholder-slate-400 transition-colors uppercase">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <th class="px-6 py-4 font-bold uppercase tracking-widest text-xs">Nama Lengkap</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-widest text-xs">Email</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-widest text-xs">Role Akses</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-widest text-xs text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-slate-100 text-slate-800 font-medium">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-100 transition-colors" wire:key="user-{{ $user->id }}">
                            <td class="px-6 py-4 uppercase text-slate-900 font-bold">{{ $user->nama }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if ($user->role === 'admin')
                                    <span
                                        class="bg-slate-900 text-white px-3 py-1 text-xs font-bold uppercase tracking-widest">ADMIN</span>
                                @elseif($user->role === 'gudang')
                                    <span
                                        class="bg-amber-500 text-white px-3 py-1 text-xs font-bold uppercase tracking-widest">GUDANG</span>
                                @else
                                    <span
                                        class="bg-emerald-500 text-white px-3 py-1 text-xs font-bold uppercase tracking-widest">KASIR</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <button wire:click="edit('{{ $user->id }}')"
                                    class="text-slate-500 hover:text-slate-900 font-bold uppercase tracking-wider text-xs transition-colors border-b-2 border-transparent hover:border-slate-900 pb-1">EDIT</button>
                                @if (auth()->id() !== $user->id)
                                    <button wire:click="delete('{{ $user->id }}')"
                                        wire:confirm="HAPUS AKUN {{ strtoupper($user->nama) }} PERMANEN?"
                                        class="text-rose-500 hover:text-rose-700 font-bold uppercase tracking-wider text-xs transition-colors border-b-2 border-transparent hover:border-rose-700 pb-1">HAPUS</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="px-6 py-12 text-center text-slate-500 font-bold uppercase tracking-widest">Tidak
                                ada pengguna ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t-2 border-slate-200 bg-slate-50">
            {{ $users->links() }}
        </div>
    </div>

    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div class="fixed inset-0 bg-slate-900 bg-opacity-80 transition-opacity backdrop-blur-sm"
                    wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="relative inline-block align-bottom bg-white text-left shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg w-full rounded-none border-t-8 border-slate-900 transform transition-all">
                    <form wire:submit="store">
                        <div class="bg-white px-6 pt-6 pb-6">
                            <h3 class="text-xl font-black text-slate-900 uppercase tracking-widest mb-6"
                                id="modal-title">
                                {{ $userId ? 'EDIT DATA PENGGUNA' : 'REGISTRASI PENGGUNA BARU' }}
                            </h3>
                            <div class="space-y-5">

                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-900 uppercase tracking-widest mb-2">Nama
                                        Lengkap</label>
                                    <input type="text" wire:model="nama"
                                        class="w-full bg-slate-50 border-2 border-slate-300 rounded-none px-4 py-3 text-slate-900 font-bold focus:outline-none focus:ring-0 focus:border-slate-900 transition-colors uppercase"
                                        placeholder="NAMA KARYAWAN">
                                    @error('nama')
                                        <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-900 uppercase tracking-widest mb-2">Email
                                        Akses</label>
                                    <input type="email" wire:model="email"
                                        class="w-full bg-slate-50 border-2 border-slate-300 rounded-none px-4 py-3 text-slate-900 font-bold focus:outline-none focus:ring-0 focus:border-slate-900 transition-colors"
                                        placeholder="email@perusahaan.com">
                                    @error('email')
                                        <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-900 uppercase tracking-widest mb-2">Pilih
                                        Role Akses</label>
                                    <select wire:model="role"
                                        class="w-full bg-slate-50 border-2 border-slate-300 rounded-none px-4 py-3 text-slate-900 font-bold focus:outline-none focus:ring-0 focus:border-slate-900 transition-colors uppercase cursor-pointer">
                                        <option value="kasir">KASIR - Transaksi Penjualan</option>
                                        <option value="gudang">GUDANG - Kelola Inventaris</option>
                                        <option value="admin">ADMIN - Akses Penuh Sistem</option>
                                    </select>
                                    @error('role')
                                        <span
                                            class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-900 uppercase tracking-widest mb-2">
                                        Password {{ $userId ? '(KOSONGKAN JIKA TETAP)' : '' }}
                                    </label>
                                    <input type="password" wire:model="password"
                                        class="w-full bg-slate-50 border-2 border-slate-300 rounded-none px-4 py-3 text-slate-900 font-bold focus:outline-none focus:ring-0 focus:border-slate-900 transition-colors"
                                        placeholder="••••••••">
                                    @error('password')
                                        <span
                                            class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-slate-100 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3 border-t-2 border-slate-200">
                            <button type="submit"
                                class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-white uppercase tracking-widest text-xs font-bold py-3 px-8 rounded-none transition-all active:scale-95 text-center">
                                SIMPAN DATA
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="w-full sm:w-auto bg-white border-2 border-slate-900 text-slate-900 hover:bg-slate-200 uppercase tracking-widest text-xs font-bold py-3 px-8 rounded-none transition-all active:scale-95 text-center">
                                BATALKAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
