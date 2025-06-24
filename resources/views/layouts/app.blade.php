{{-- resources/views/components/app-layout.blade.php --}}
@props(['title' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' | ' : '' }}{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Calender -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

    
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-white">
        <!-- Navbar -->
        @include('layouts.navigation')

        <!-- Sidebar Dinamis Berdasarkan Role -->
        @if (Auth::check())
            @if (Auth::user()->role == 'admin')
                <x-sidebar-admin />
            @elseif(Auth::user()->role == 'petugas_security')
                <x-sidebar-petugas />
            @elseif(Auth::user()->role == 'kabid_dukbis')
                <x-sidebar-dukbis />
            @endif
        @endif

        <!-- Page Content -->
       <main class="relative pt-16 md:ml-64 p-4 bg-white min-h-screen">
            {{ $slot }}
            
            <x-alert />
        </main>
    </div>

    <!-- FLOWBITE JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- CHART -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Calender -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</body>

</html>
