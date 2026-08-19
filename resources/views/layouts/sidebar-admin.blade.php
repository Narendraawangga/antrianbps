@vite('resources/css/layouts/sidebar-admin.css')

<aside class="admin-sidebar">

    <!-- HEADER SIDEBAR -->
    <div class="sidebar-header">

        <div class="sidebar-title">
            ADMIN PANEL
        </div>

        <div class="sidebar-subtitle">
            Sistem Antrian BPS
        </div>

    </div>


    <!-- MENU UTAMA -->
    <div class="sidebar-section">

        <div class="section-title">
            MENU UTAMA
        </div>


        <!-- DASHBOARD -->
        <a
            href="{{ route('dashboard') }}"
            class="admin-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">

            <span class="menu-icon">
                🏠
            </span>

            <span class="menu-text">
                Dashboard
            </span>

        </a>


        <!-- ANTREAN -->
        <a href="{{ route('admin.antrean') }}"
            class="admin-menu-item {{ request()->routeIs('admin.antrean') ? 'active' : '' }}">

            <span class="menu-icon">
                🎫
            </span>

            <span class="menu-text">
                Antrean
            </span>

        </a>


        <!-- JADWAL PETUGAS -->
        <a
            href="{{ route('admin.jadwal') }}"
            class="admin-menu-item {{ request()->routeIs('admin.jadwal*') ? 'active' : '' }}">

            <span class="menu-icon">
                📅
            </span>

            <span class="menu-text">
                Jadwal Petugas
            </span>

        </a>

    </div>


    <!-- MANAJEMEN -->
    <div class="sidebar-section">

        <div class="section-title">
            MANAJEMEN
        </div>


        <!-- PENGGUNA -->
        <a
            href="{{ route('admin.users') }}"
            class="admin-menu-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">

            <span class="menu-icon">
                👥
            </span>

            <span class="menu-text">
                Pengguna
            </span>

        </a>


        <!-- LAYANAN -->
        <a
            href="#"
            class="admin-menu-item">

            <span class="menu-icon">
                🏢
            </span>

            <span class="menu-text">
                Layanan
            </span>

        </a>


        <!-- LAPORAN -->
        <a
            href="#"
            class="admin-menu-item">

            <span class="menu-icon">
                📊
            </span>

            <span class="menu-text">
                Laporan
            </span>

        </a>

    </div>


    <!-- SISTEM -->
    <div class="sidebar-section">

        <div class="section-title">
            SISTEM
        </div>


        <!-- PENGATURAN -->
        <a
            href="#"
            class="admin-menu-item">

            <span class="menu-icon">
                ⚙️
            </span>

            <span class="menu-text">
                Pengaturan
            </span>

        </a>

    </div>


    <!-- BAGIAN BAWAH -->
    <div class="sidebar-bottom">

        <div class="system-status">

            <span class="status-dot"></span>

            <span>
                Sistem Online
            </span>

        </div>


        <div class="sidebar-version">

            BPS Kolaka Utara

            <br>

            <span>
                Admin Panel v1.0
            </span>

        </div>

    </div>

</aside>