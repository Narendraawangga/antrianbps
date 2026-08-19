<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Petugas - Sistem Antrian BPS')
    </title>

    @vite('resources/css/layouts/petugas.css')

    @stack('styles')

</head>

<body>

    {{-- NAVBAR PETUGAS --}}
    @include('layouts.navbar-petugas')


    {{-- SIDEBAR PETUGAS --}}
    @include('layouts.sidebar-petugas')


    {{-- ISI HALAMAN PETUGAS --}}
    <main class="petugas-main-content">

        @yield('content')

    </main>


    @stack('scripts')

</body>

</html>