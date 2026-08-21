<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin - Sistem Antrian BPS')
    </title>

    @vite('resources/css/layouts/admin.css')

    @stack('styles')

</head>

<body>

    {{-- NAVBAR ADMIN --}}
    @include('layouts.navbar-admin')


    {{-- SIDEBAR ADMIN --}}
    @include('layouts.sidebar-admin')


    {{-- ISI HALAMAN ADMIN --}}
    <main class="admin-main-content">

        @yield('content')

    </main>


    @stack('scripts')

</body>

</html>