@vite('resources/css/layouts/navbar-admin.css')

<nav class="admin-navbar">

    <div class="navbar-left">

        <div class="logo-container">
            <img
                src="{{ asset('images/logo-bps.png') }}"
                alt="Logo BPS"
                class="logo-bps"
            >
        </div>

        <div class="system-info">

            <div class="system-name">
                BPS KABUPATEN KOLAKA UTARA
            </div>

            <div class="system-subtitle">
                Sistem Antrian Pelayanan
            </div>

        </div>

    </div>


    <div class="navbar-right">

        <div class="profile">

            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="profile-info">

                <div class="profile-name">
                    {{ auth()->user()->name }}
                </div>

                <div class="profile-role">
                    Admin Utama
                </div>

            </div>

        </div>


        <form
            action="{{ route('logout') }}"
            method="POST"
            class="logout-form"
        >
            @csrf

            <button
                type="submit"
                class="logout-button"
            >
                Logout
            </button>

        </form>

    </div>

</nav>