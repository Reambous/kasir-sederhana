<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        /* Memaksa seluruh aplikasi menggunakan font Inter */
        body {
            font-family: 'Inter', sans-serif !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-800 selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col">
        <livewire:layout.navigation />

        @if (isset($header))
            <header class="bg-white border-b border-slate-200 shadow-sm">
                <div class="w-full mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="flex-1 w-full mx-auto py-8 px-2 sm:px-4">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
