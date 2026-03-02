<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS SYSTEM') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-kasir.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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



        <div class="z-10 mb-8 text-center pt-10 sm:pt-16 no-print">

            <div class=" ">
                <x-application-logo class="w-48 h-48 object-contain" />
            </div>
            <span class="text-white font-black text-2xl tracking-[0.2em] uppercase hidden sm:block ">
                POS<span class="text-indigo-500">SYS</span>
            </span>

        </div>

        <div
            class="z-10 w-full sm:max-w-md px-8 py-10 bg-white shadow-2xl sm:rounded-none border-t-4 border-indigo-600">
            {{ $slot }}
        </div>

        <div class="z-10 mt-8 text-slate-500 text-xs text-center font-medium">
            &copy; {{ date('Y') }} POSSYS. All rights reserved.<br>
            Restricted System Access.
        </div>
    </div>
</body>

</html>
