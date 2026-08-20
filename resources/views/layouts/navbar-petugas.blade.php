@vite('resources/css/layouts/navbar-petugas.css')

<header class="petugas-navbar">

    <!-- BAGIAN KIRI -->
    <div class="navbar-left">

        <!-- LOGO BPS -->
        <div class="navbar-logo">
            <img
                src="{{ asset('images/hh.png') }}"
                alt="Logo BPS"
                class="logo-bps">
        </div>


        <!-- IDENTITAS SISTEM -->
        <div class="navbar-title">

            <div class="institution-name">
                BPS KABUPATEN KOLAKA UTARA
            </div>

            <div class="system-name">
                Sistem Antrian Pelayanan
            </div>

        </div>

    </div>


    <!-- BAGIAN KANAN -->
    <div class="navbar-right">

        <!-- PROFIL PETUGAS -->
        <div class="petugas-profile">

            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="profile-info">

                <div class="profile-name">
                    {{ auth()->user()->name }}
                </div>

                <div class="profile-role">
                    Petugas
                </div>

            </div>

        </div>


        <!-- LOGOUT -->
        <form
            action="{{ route('logout') }}"
            method="POST"
            class="logout-form">
            @csrf

            <button
                type="submit"
                class="logout-button">
                Logout
            </button>

        </form>

    </div>

</header>