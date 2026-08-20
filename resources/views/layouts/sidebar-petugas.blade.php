@vite('resources/css/layouts/sidebar-petugas.css')

<aside class="petugas-sidebar">

    <!-- HEADER -->
    <div class="sidebar-header">

        <div class="sidebar-title">
            PETUGAS PANEL
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
            href="{{ route('petugas.dashboard') }}"
            class="petugas-menu-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">

            <span class="menu-icon">
                🏠
            </span>

            <span class="menu-text">
                Dashboard
            </span>

        </a>


        <!-- ANTREAN -->
        <a
            href="#"
            class="petugas-menu-item">

            <span class="menu-icon">
                🎫
            </span>

            <span class="menu-text">
                Antrean
            </span>

        </a>


        <!-- JADWAL SAYA -->
        <a
            href="{{ route('petugas.jadwal') }}"
            class="petugas-menu-item {{ request()->routeIs('petugas.jadwal') ? 'active' : '' }}">

            <span class="menu-icon">
                📅
            </span>

            <span class="menu-text">
                Jadwal Saya
            </span>

        </a>


        <!-- RIWAYAT -->
        <a
            href="{{ route('petugas.riwayat') }}"
            class="petugas-menu-item {{ request()->routeIs('petugas.riwayat') ? 'active' : '' }}">

            <span class="menu-icon">
                📊
            </span>

            <span class="menu-text">
                Riwayat Layanan
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
            class="petugas-menu-item">

            <span class="menu-icon">
                ⚙️
            </span>

            <span class="menu-text">
                Pengaturan
            </span>

        </a>

    </div>


    <!-- BOTTOM -->
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
                Petugas Panel v1.0
            </span>

        </div>

    </div>

</aside>