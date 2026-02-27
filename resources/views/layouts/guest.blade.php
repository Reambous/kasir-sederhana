<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS Login') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-slate-900 antialiased">
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-900 relative overflow-hidden">

        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-20 pointer-events-none">
            <div
                class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl">
            </div>
            <div class="absolute top-48 right-12 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl">
            </div>
        </div>

        <div class="z-10 mb-8 text-center">
            <a href="/" wire:navigate class="flex flex-col items-center gap-2">
                <div class="w-16 h-16 bg-indigo-600 rounded-xl shadow-lg flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="text-3xl font-black tracking-wider text-white mt-2">NAMA_TOKO_KAMU</span>
                <span class="text-sm tracking-widest text-slate-400 font-semibold uppercase">Enterprise POS
                    System</span>
            </a>
        </div>

        <div
            class="z-10 w-full sm:max-w-md px-8 py-10 bg-white shadow-2xl sm:rounded-none border-t-4 border-indigo-600">
            {{ $slot }}
        </div>

        <div class="z-10 mt-8 text-slate-500 text-xs text-center font-medium">
            &copy; {{ date('Y') }} Nama Perusahaan. All rights reserved.<br>
            Restricted System Access.
        </div>
    </div>
</body>

</html>
