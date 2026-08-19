<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manajemen Pengguna - BPS Kolaka Utara</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #222;
            overflow-x: hidden;
        }

        /* ==============================
           CONTENT
        ============================== */

        .admin-content {
            margin-left: 250px;
            padding-top: 72px;
            min-height: 100vh;
        }

        .content {
            padding: 28px;
            max-width: 1600px;
            margin: auto;
        }

        /* ==============================
           HEADER
        ============================== */

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .page-title h1 {
            color: #1d2f5f;
            font-size: 25px;
            margin-bottom: 6px;
        }

        .page-title p {
            color: #777;
            font-size: 14px;
        }

        /* ==============================
           ADD BUTTON
        ============================== */

        .add-user-btn {
            border: none;
            background: #2349ad;
            color: white;

            padding: 11px 18px;

            border-radius: 8px;

            font-size: 13px;
            font-weight: 600;

            cursor: pointer;

            display: flex;
            align-items: center;
            gap: 8px;

            transition: .2s;
        }

        .add-user-btn:hover {
            background: #193b92;
        }

        /* ==============================
           STATISTICS
        ============================== */

        .user-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-label {
            color: #777;
            font-size: 12px;
            margin-bottom: 7px;
        }

        .stat-number {
            color: #1d2f5f;
            font-size: 27px;
            font-weight: 700;
        }

        .stat-icon {
            width: 45px;
            height: 45px;

            border-radius: 9px;

            background: #eef3ff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 20px;
        }

        /* ==============================
           TABLE CARD
        ============================== */

        .users-card {
            background: white;

            border: 1px solid #e5e7eb;

            border-radius: 10px;

            overflow: hidden;

            box-shadow:
                0 2px 6px rgba(0, 0, 0, .03);
        }

        .card-header {
            padding: 18px 20px;

            border-bottom: 1px solid #eee;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .card-header h2 {
            font-size: 16px;
            color: #25365d;
        }

        .search-box {
            width: 230px;

            height: 36px;

            border: 1px solid #ddd;

            border-radius: 7px;

            padding: 0 12px;

            outline: none;

            font-size: 12px;
        }

        .search-box:focus {
            border-color: #2349ad;
        }

        /* ==============================
           TABLE
        ============================== */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
        }

        th {
            padding: 13px 20px;

            background: #fafbfc;

            text-align: left;

            color: #777;

            font-size: 11px;

            font-weight: 600;
        }

        td {
            padding: 14px 20px;

            border-top: 1px solid #f0f0f0;

            font-size: 13px;

            color: #444;
        }

        /* ==============================
           USER
        ============================== */

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 36px;
            height: 36px;

            border-radius: 50%;

            background: #2349ad;

            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 13px;
            font-weight: bold;
        }

        .user-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .user-email {
            color: #999;
            font-size: 11px;
        }

        /* ==============================
           ROLE
        ============================== */

        .role {
            display: inline-block;

            padding: 5px 9px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 600;
        }

        .role.super-admin {
            background: #eee9ff;
            color: #6b3fb5;
        }

        .role.admin {
            background: #e8f0ff;
            color: #2349ad;
        }

        .role.petugas {
            background: #e5f7ec;
            color: #168044;
        }

        /* ==============================
           STATUS
        ============================== */

        .status {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            font-size: 11px;

            color: #168044;
        }

        .status-dot {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: #22c55e;
        }

        /* ==============================
           ACTION
        ============================== */

        .actions {
            display: flex;
            gap: 7px;
        }

        .action-btn {
            border: 1px solid #ddd;

            background: white;

            padding: 7px 10px;

            border-radius: 6px;

            font-size: 11px;

            cursor: pointer;

            transition: .2s;
        }

        .action-btn:hover {
            background: #f5f7fb;
        }

        .action-btn.delete:hover {
            color: #dc2626;
            border-color: #fecaca;
            background: #fff5f5;
        }

        /* ==============================
           EMPTY
        ============================== */

        .empty-state {
            text-align: center;
            padding: 55px 20px;
            color: #999;
        }

        .empty-icon {
            font-size: 40px;
            margin-bottom: 12px;
        }

        .empty-state h3 {
            color: #555;
            font-size: 16px;
            margin-bottom: 6px;
        }

        .empty-state p {
            font-size: 12px;
        }

        /* ==============================
           MODAL
        ============================== */

        .modal {
            position: fixed;

            inset: 0;

            background: rgba(0, 0, 0, .45);

            display: none;

            align-items: center;
            justify-content: center;

            padding: 20px;

            z-index: 2000;
        }

        .modal.show {
            display: flex;
        }

        .modal-box {
            width: 480px;
            max-width: 100%;

            background: white;

            border-radius: 12px;

            box-shadow:
                0 15px 50px rgba(0, 0, 0, .2);

            overflow: hidden;
        }

        .modal-header {
            padding: 18px 20px;

            border-bottom: 1px solid #eee;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h2 {
            color: #25365d;
            font-size: 17px;
        }

        .close-modal {
            border: none;
            background: transparent;

            font-size: 22px;

            color: #888;

            cursor: pointer;
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;

            margin-bottom: 7px;

            color: #555;

            font-size: 12px;

            font-weight: 600;
        }

        .form-input,
        .form-select {
            width: 100%;

            height: 40px;

            border: 1px solid #ddd;

            border-radius: 7px;

            padding: 0 12px;

            outline: none;

            font-size: 13px;

            background: white;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #2349ad;
        }

        .modal-footer {
            padding: 15px 20px;

            border-top: 1px solid #eee;

            display: flex;

            justify-content: flex-end;

            gap: 8px;
        }

        .cancel-btn {
            border: 1px solid #ddd;

            background: white;

            color: #555;

            padding: 10px 16px;

            border-radius: 7px;

            cursor: pointer;
        }

        .save-btn {
            border: none;

            background: #2349ad;

            color: white;

            padding: 10px 18px;

            border-radius: 7px;

            cursor: pointer;
        }

        .field-help {
            display: block;
            margin-top: 5px;
            color: #999;
            font-size: 10px;
        }

        .field-error {
            display: block;
            margin-top: 5px;
            color: #dc2626;
            font-size: 11px;
            min-height: 14px;
        }

        .form-input.input-error,
        .form-select.input-error {
            border-color: #dc2626;
            background: #fff8f8;
        }

        .form-input.input-success {
            border-color: #22c55e;
            background: #f7fff9;
        }

        /* ==============================
           MOBILE
        ============================== */

        @media (max-width: 700px) {

            .admin-content {
                margin-left: 70px;
            }

            .content {
                padding: 20px 15px;
            }

            .page-header {
                align-items: flex-start;
            }

            .page-title h1 {
                font-size: 21px;
            }

            .page-title p {
                font-size: 12px;
                line-height: 1.5;
            }

            .add-user-btn {
                padding: 10px 12px;
            }

            .add-user-btn span:last-child {
                display: none;
            }

            .user-stats {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .user-stats .stat-card:last-child {
                grid-column: span 2;
            }

            .card-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                width: 100%;
            }
        }

        @media (max-width: 450px) {

            .admin-content {
                margin-left: 0;
            }

            .content {
                padding: 18px 12px;
            }

            .page-header {
                flex-direction: column;
            }

            .add-user-btn {
                width: 100%;
                justify-content: center;
            }

            .add-user-btn span:last-child {
                display: inline;
            }

            .user-stats {
                grid-template-columns: 1fr;
            }

            .user-stats .stat-card:last-child {
                grid-column: auto;
            }

            .stat-card {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    @include('layouts.navbar-admin')

    {{-- SIDEBAR --}}
    @include('layouts.sidebar-admin')


    <!-- ==============================
         CONTENT
    =============================== -->

    <main class="admin-content">

        <div class="content">


            <!-- HEADER -->

            <div class="page-header">

                <div class="page-title">

                    <h1>
                        Manajemen Pengguna
                    </h1>

                    <p>
                        Kelola akun administrator dan petugas
                        sistem antrian BPS Kolaka Utara.
                    </p>

                </div>


                <button
                    type="button"
                    class="add-user-btn"
                    onclick="openUserModal()">

                    <span>
                        ＋
                    </span>

                    <span>
                        Tambah Pengguna
                    </span>

                </button>

            </div>


            <!-- ==============================
                 STATISTIK USER
            =============================== -->

            <div class="user-stats">

                <div class="stat-card">

                    <div>

                        <div class="stat-label">
                            Total Pengguna
                        </div>

                        <div class="stat-number">
                            {{ $totalUsers }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        👥
                    </div>

                </div>


                <div class="stat-card">

                    <div>

                        <div class="stat-label">
                            Administrator
                        </div>

                        <div class="stat-number">
                            {{ $totalAdmin }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        🛡️
                    </div>

                </div>


                <div class="stat-card">

                    <div>

                        <div class="stat-label">
                            Petugas
                        </div>

                        <div class="stat-number">
                            {{ $totalPetugas }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        👨‍💼
                    </div>

                </div>

            </div>


            <!-- ==============================
                 USER TABLE
            =============================== -->

            <div class="users-card">

                <div class="card-header">

                    <h2>
                        Daftar Pengguna
                    </h2>

                    <input
                        type="search"
                        class="search-box"
                        placeholder="Cari pengguna...">

                </div>


                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    PENGGUNA
                                </th>

                                <th>
                                    USERNAME
                                </th>

                                <th>
                                    ROLE
                                </th>

                                <th>
                                    STATUS
                                </th>

                                <th>
                                    AKSI
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <!-- DATA NANTI DARI DATABASE -->

                            <tr>

                                <td colspan="5">

                                    <div class="empty-state">

                                        <div class="empty-icon">
                                            👥
                                        </div>

                                        <h3>
                                            Belum ada pengguna
                                        </h3>

                                        <p>
                                            Tambahkan akun Admin atau
                                            Petugas untuk mulai menggunakan
                                            sistem.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </main>


    <!-- ==============================
         MODAL TAMBAH PENGGUNA
    =============================== -->

    <div class="modal" id="userModal">

        <div class="modal-box">

            <div class="modal-header">

                <h2>
                    Tambah Pengguna
                </h2>

                <button
                    type="button"
                    class="close-modal"
                    onclick="closeUserModal()">
                    ×
                </button>

            </div>


            <form
                method="POST"
                action="{{ route('admin.users.store') }}"
                id="userForm">

                @csrf


                <div class="modal-body">

                    {{-- NAMA --}}

                    <div class="form-group">

                        <label>
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="userName"
                            class="form-input"
                            placeholder="Masukkan nama lengkap"
                            required>

                        <small
                            class="field-error"
                            id="nameError">
                        </small>

                    </div>


                    {{-- USERNAME --}}

                    <div class="form-group">

                        <label>
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="form-input"
                            placeholder="Masukkan username"
                            minlength="4"
                            maxlength="30"
                            autocomplete="off"
                            required>

                        <small class="field-help">
                            Minimal 4 karakter.
                        </small>

                        <small
                            class="field-error"
                            id="usernameError">
                        </small>

                    </div>


                    {{-- PASSWORD --}}

                    <div class="form-group">

                        <label>
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-input"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            autocomplete="new-password"
                            required>

                        <small class="field-help">
                            Password minimal 8 karakter.
                        </small>

                        <small
                            class="field-error"
                            id="passwordError">
                        </small>

                    </div>


                    {{-- KONFIRMASI PASSWORD --}}

                    <div class="form-group">

                        <label>
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            id="passwordConfirmation"
                            class="form-input"
                            placeholder="Ulangi password"
                            minlength="8"
                            autocomplete="new-password"
                            required>

                        <small
                            class="field-error"
                            id="confirmationError">
                        </small>

                    </div>


                    {{-- ROLE --}}

                    <div class="form-group">

                        <label>
                            Role
                        </label>

                        <select
                            name="role"
                            id="role"
                            class="form-select"
                            required>

                            <option value="">
                                Pilih Role
                            </option>

                            <option value="admin">
                                Administrator
                            </option>

                            <option value="petugas">
                                Petugas
                            </option>

                        </select>

                        <small
                            class="field-error"
                            id="roleError">
                        </small>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="cancel-btn"
                        onclick="closeUserModal()">

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="save-btn"
                        id="saveUserButton">

                        Simpan Pengguna

                    </button>

                </div>

            </form>

        </div>

    </div>


    <script>
        const userForm = document.getElementById('userForm');

        const usernameInput =
            document.getElementById('username');

        const passwordInput =
            document.getElementById('password');

        const confirmationInput =
            document.getElementById('passwordConfirmation');

        // ========================================
        // MODAL TAMBAH PENGGUNA
        // ========================================

        function openUserModal() {
            const modal = document.getElementById('userModal');

            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }


        function closeUserModal() {
            const modal = document.getElementById('userModal');

            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        }


        // Klik area luar modal untuk menutup
        document.getElementById('userModal').addEventListener(
            'click',
            function(event) {

                if (event.target === this) {
                    closeUserModal();
                }

            }
        );


        // Tekan ESC untuk menutup
        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {
                closeUserModal();
            }

        });

        // ========================================
        // CEK USERNAME
        // ========================================

        usernameInput.addEventListener('input', function() {

            const username = this.value.trim();

            const error =
                document.getElementById('usernameError');

            this.classList.remove('input-error');
            error.textContent = '';

            if (username.length > 0 && username.length < 4) {

                this.classList.add('input-error');

                error.textContent =
                    'Username minimal 4 karakter.';

                return;
            }

        });


        // ========================================
        // CEK PASSWORD
        // ========================================

        passwordInput.addEventListener('input', function() {

            const password = this.value;

            const error =
                document.getElementById('passwordError');

            this.classList.remove('input-error');

            error.textContent = '';

            if (
                password.length > 0 &&
                password.length < 8
            ) {

                this.classList.add('input-error');

                error.textContent =
                    'Password minimal 8 karakter.';
            }

            checkPasswordMatch();

        });


        // ========================================
        // CEK KONFIRMASI PASSWORD
        // ========================================

        confirmationInput.addEventListener(
            'input',
            checkPasswordMatch
        );


        function checkPasswordMatch() {

            const password =
                passwordInput.value;

            const confirmation =
                confirmationInput.value;

            const error =
                document.getElementById(
                    'confirmationError'
                );

            confirmationInput.classList.remove(
                'input-error'
            );

            error.textContent = '';

            if (
                confirmation.length > 0 &&
                password !== confirmation
            ) {

                confirmationInput.classList.add(
                    'input-error'
                );

                error.textContent =
                    'Konfirmasi password tidak sama.';

            } else if (
                confirmation.length >= 8 &&
                password === confirmation
            ) {

                confirmationInput.classList.add(
                    'input-success'
                );

            }

        }


        // ========================================
        // SUBMIT
        // ========================================

        userForm.addEventListener(
            'submit',
            function(event) {

                let valid = true;


                const name =
                    document.getElementById(
                        'userName'
                    ).value.trim();

                const username =
                    usernameInput.value.trim();

                const password =
                    passwordInput.value;

                const confirmation =
                    confirmationInput.value;

                const role =
                    document.getElementById(
                        'role'
                    ).value;


                // NAMA

                if (!name) {

                    document.getElementById(
                            'nameError'
                        ).textContent =
                        'Nama lengkap wajib diisi.';

                    valid = false;

                } else {

                    document.getElementById(
                        'nameError'
                    ).textContent = '';

                }


                // USERNAME

                if (!username) {

                    document.getElementById(
                            'usernameError'
                        ).textContent =
                        'Username wajib diisi.';

                    usernameInput.classList.add(
                        'input-error'
                    );

                    valid = false;

                } else if (username.length < 4) {

                    document.getElementById(
                            'usernameError'
                        ).textContent =
                        'Username minimal 4 karakter.';

                    usernameInput.classList.add(
                        'input-error'
                    );

                    valid = false;

                }


                // PASSWORD

                if (password.length < 8) {

                    document.getElementById(
                            'passwordError'
                        ).textContent =
                        'Password minimal 8 karakter.';

                    passwordInput.classList.add(
                        'input-error'
                    );

                    valid = false;

                }


                // KONFIRMASI

                if (password !== confirmation) {

                    document.getElementById(
                            'confirmationError'
                        ).textContent =
                        'Konfirmasi password tidak sama.';

                    confirmationInput.classList.add(
                        'input-error'
                    );

                    valid = false;

                }


                // ROLE

                if (!role) {

                    document.getElementById(
                            'roleError'
                        ).textContent =
                        'Silakan pilih role.';

                    valid = false;

                }


                if (!valid) {

                    event.preventDefault();

                    return;

                }

            }
        );
    </script>

</body>

</html>