<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-slate-900 uppercase tracking-wider">
            {{ __('Otoritas Profil Pengguna') }}
        </h2>
    </x-slot>

    <div class="w-full space-y-6">
        <div
            class="bg-slate-900 text-white border-b-8 border-indigo-600 p-8 shadow-xl flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 -translate-y-1/2">
            </div>

            <div
                class="w-32 h-32 bg-slate-800 border-4 border-white shadow-[8px_8px_0px_rgba(0,0,0,1)] flex items-center justify-center relative z-10">
                @if (auth()->user()->gambar)
                    <img src="{{ asset('storage/' . auth()->user()->gambar) }}" class="w-full h-full object-cover">
                @else
                    <span
                        class="text-5xl font-black text-indigo-500 uppercase">{{ substr(auth()->user()->nama, 0, 1) }}</span>
                @endif
            </div>

            <div class="relative z-10 text-center md:text-left flex-1">
                <h3 class="text-4xl font-black uppercase tracking-tighter">{{ auth()->user()->nama }}</h3>
                <div class="mt-2 flex flex-wrap justify-center md:justify-start gap-2">
                    <span
                        class="bg-indigo-600 text-[10px] font-black px-3 py-1 uppercase tracking-widest border border-indigo-400">ROLE:
                        {{ auth()->user()->role }}</span>
                    <span
                        class="bg-slate-800 text-[10px] font-black px-3 py-1 uppercase tracking-widest border border-slate-700">ID:
                        {{ substr(auth()->user()->id, 0, 8) }}...</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white border-2 border-slate-900 p-6 shadow-[6px_6px_0px_rgba(0,0,0,1)]">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="bg-white border-2 border-slate-900 p-6 shadow-[6px_6px_0px_rgba(0,0,0,1)]">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
