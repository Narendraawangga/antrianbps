@extends('layouts.admin')

@section('title', 'Dashboard Admin - BPS Kolaka Utara')


@push('styles')
    @vite('resources/css/admin/dashboard.css')
@endpush


@section('content')

    {{-- OVERLAY MOBILE --}}
    <div
        class="sidebar-overlay"
        id="sidebarOverlay">
    </div>


    <div class="dashboard-content">

        <div class="content-inner">

            {{-- HEADER --}}
            <div class="page-header">

                <div class="page-title">

                    <h1>
                        Dashboard
                    </h1>

                    <p>
                        Selamat datang di Sistem Antrian
                        Pelayanan BPS Kabupaten Kolaka Utara.
                    </p>

                </div>


                <div class="date-box">

                    📅

                    {{ now()->translatedFormat('d F Y') }}

                </div>

            </div>


            {{-- STATISTIK --}}
            <div class="stats">

                <div class="stat-card">

                    <div class="stat-info">

                        <span>
                            Total Antrean
                        </span>

                        <strong>
                            42
                        </strong>

                    </div>

                    <div class="stat-icon">
                        🎫
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-info">

                        <span>
                            Menunggu
                        </span>

                        <strong>
                            12
                        </strong>

                    </div>

                    <div class="stat-icon">
                        ⏳
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-info">

                        <span>
                            Dilayani
                        </span>

                        <strong>
                            3
                        </strong>

                    </div>

                    <div class="stat-icon">
                        👤
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-info">

                        <span>
                            Selesai
                        </span>

                        <strong>
                            27
                        </strong>

                    </div>

                    <div class="stat-icon">
                        ✓
                    </div>

                </div>

            </div>


            {{-- DASHBOARD GRID --}}
            <div class="dashboard-grid">


                {{-- ANTREAN TERBARU --}}
                <div class="card">

                    <div class="card-header">

                        <h2>
                            Antrean Terbaru
                        </h2>

                        <a
                            href="#"
                            class="view-all">
                            Lihat Semua
                        </a>

                    </div>


                    <div class="table-wrapper">

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        Nomor
                                    </th>

                                    <th>
                                        Layanan
                                    </th>

                                    <th>
                                        Waktu
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <tr>

                                    <td>
                                        <span class="queue-number">
                                            A-005
                                        </span>
                                    </td>

                                    <td>
                                        Perpustakaan
                                    </td>

                                    <td>
                                        08:15
                                    </td>

                                    <td>
                                        <span class="status waiting">
                                            Menunggu
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <span class="queue-number">
                                            A-006
                                        </span>
                                    </td>

                                    <td>
                                        Perpustakaan
                                    </td>

                                    <td>
                                        08:20
                                    </td>

                                    <td>
                                        <span class="status waiting">
                                            Menunggu
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <span class="queue-number">
                                            B-003
                                        </span>
                                    </td>

                                    <td>
                                        Konsultasi
                                    </td>

                                    <td>
                                        08:27
                                    </td>

                                    <td>
                                        <span class="status serving">
                                            Dilayani
                                        </span>
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- MENU CEPAT --}}
                <div class="card">

                    <div class="card-header">

                        <h2>
                            Menu Cepat
                        </h2>

                    </div>


                    <div class="quick-menu">

                        <a
                            href="#"
                            class="quick-item">

                            <div class="quick-icon">
                                🎫
                            </div>

                            <strong>
                                Antrean
                            </strong>

                            <span>
                                Kelola antrean
                            </span>

                        </a>


                        <a
                            href="{{ route('admin.users') }}"
                            class="quick-item">

                            <div class="quick-icon">
                                👥
                            </div>

                            <strong>
                                Pengguna
                            </strong>

                            <span>
                                Kelola akun
                            </span>

                        </a>


                        <a
                            href="#"
                            class="quick-item">

                            <div class="quick-icon">
                                📅
                            </div>

                            <strong>
                                Jadwal
                            </strong>

                            <span>
                                Jadwal petugas
                            </span>

                        </a>


                        <a
                            href="#"
                            class="quick-item">

                            <div class="quick-icon">
                                📊
                            </div>

                            <strong>
                                Laporan
                            </strong>

                            <span>
                                Laporan pelayanan
                            </span>

                        </a>

                    </div>

                </div>


                {{-- LAYANAN HARI INI --}}
                <div class="card">

                    <div class="card-header">

                        <h2>
                            Layanan Hari Ini
                        </h2>

                    </div>


                    <div class="service-list">

                        <div class="service-row">

                            <span class="service-name">
                                Pelayanan Perpustakaan
                            </span>

                            <span class="service-count">
                                18
                            </span>

                        </div>


                        <div class="service-row">

                            <span class="service-name">
                                Pelayanan Konsultasi
                            </span>

                            <span class="service-count">
                                15
                            </span>

                        </div>


                        <div class="service-row">

                            <span class="service-name">
                                Penjualan Produk Statistik
                            </span>

                            <span class="service-count">
                                5
                            </span>

                        </div>


                        <div class="service-row">

                            <span class="service-name">
                                Pelayanan Rekomendasi
                            </span>

                            <span class="service-count">
                                4
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    @vite('resources/js/admin/dashboard.js')
@endpush