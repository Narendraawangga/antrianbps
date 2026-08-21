<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Manajemen Pengguna - BPS Kolaka Utara
    </title>


    <style>
        /* =========================================================
           RESET
        ========================================================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f5f7fb;
            color: #222;

            overflow-x: hidden;
        }


        /* =========================================================
           CONTENT
        ========================================================= */

        .admin-content {
            margin-left: 250px;
            padding-top: 72px;

            min-height: 100vh;
        }


        .content {
            max-width: 1600px;

            margin: auto;

            padding: 28px;
        }


        /* =========================================================
           HEADER
        ========================================================= */

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


        /* =========================================================
           BUTTON TAMBAH
        ========================================================= */

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


        /* =========================================================
           ALERT
        ========================================================= */

        .alert {
            padding: 13px 16px;

            margin-bottom: 20px;

            border-radius: 8px;

            font-size: 13px;
        }


        .alert-success {
            color: #166534;

            background: #dcfce7;

            border: 1px solid #bbf7d0;
        }


        .alert-error {
            color: #991b1b;

            background: #fee2e2;

            border: 1px solid #fecaca;
        }


        /* =========================================================
           STATISTIK
        ========================================================= */

        .user-stats {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

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


        /* =========================================================
           CARD
        ========================================================= */

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
            width: 250px;
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


        /* =========================================================
           TABLE
        ========================================================= */

        .table-wrapper {
            width: 100%;

            overflow-x: auto;
        }


        table {
            width: 100%;

            min-width: 850px;

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


        /* =========================================================
           USER
        ========================================================= */

        .user-profile {
            display: flex;

            align-items: center;

            gap: 10px;
        }


        .avatar {
            width: 36px;
            height: 36px;

            flex-shrink: 0;

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


        .user-username {
            color: #999;

            font-size: 11px;
        }


        /* =========================================================
           ROLE
        ========================================================= */

        .role {
            display: inline-block;

            padding: 5px 9px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 600;
        }


        .role.admin-utama {
            color: #6b3fb5;

            background: #eee9ff;
        }


        .role.petugas {
            color: #168044;

            background: #e5f7ec;
        }


        /* =========================================================
           PELAYANAN
        ========================================================= */

        .service-badge {
            display: inline-block;

            max-width: 240px;

            padding: 6px 10px;

            border-radius: 7px;

            color: #2349ad;

            background: #eef3ff;

            font-size: 11px;

            line-height: 1.4;
        }


        .service-none {
            color: #999;

            font-size: 12px;
        }

        /* =====================================
   ACTION
===================================== */

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .action-buttons form {
            margin: 0;
        }

        .action-btn {
            width: 34px;
            height: 34px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            border-radius: 7px;

            border: 1px solid transparent;

            text-decoration: none;

            cursor: pointer;

            transition: .2s ease;
        }

        .action-btn svg {
            width: 16px;
            height: 16px;

            fill: none;

            stroke: currentColor;

            stroke-width: 1.8;

            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .action-btn.edit {
            background: #eff6ff;

            color: #2563eb;

            border-color: #dbeafe;
        }

        .action-btn.edit:hover {
            background: #dbeafe;
        }

        .action-btn.delete {
            background: #fef2f2;

            color: #dc2626;

            border-color: #fecaca;
        }

        .action-btn.delete:hover {
            background: #fee2e2;
        }

        /* =========================================================
           EMPTY
        ========================================================= */

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


        /* =========================================================
           MODAL
        ========================================================= */

        .modal {
            position: fixed;

            inset: 0;

            display: none;

            align-items: center;
            justify-content: center;

            padding: 20px;

            background:
                rgba(0, 0, 0, .45);

            z-index: 2000;
        }


        .modal.show {
            display: flex;
        }


        .modal-box {
            width: 500px;

            max-width: 100%;

            max-height: 90vh;

            background: white;

            border-radius: 12px;

            box-shadow:
                0 15px 50px rgba(0, 0, 0, .2);

            overflow-y: auto;
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


        /* =========================================================
           FORM
        ========================================================= */

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


        .required {
            color: #dc2626;
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


        .form-input.input-error,
        .form-select.input-error {
            border-color: #dc2626;

            background: #fff8f8;
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
        }


        /*
        |--------------------------------------------------------------------------
        | PELAYANAN
        |--------------------------------------------------------------------------
        */

        #serviceGroup[hidden] {
            display: none;
        }


        .service-info {
            padding: 10px 12px;

            margin-bottom: 16px;

            color: #1e40af;

            background: #eff6ff;

            border:
                1px solid #dbeafe;

            border-radius: 7px;

            font-size: 11px;

            line-height: 1.5;
        }


        /* =========================================================
           MODAL FOOTER
        ========================================================= */

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


        /* =========================================================
           RESPONSIVE
        ========================================================= */

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


            .user-stats {
                grid-template-columns:
                    1fr 1fr;
            }


            .user-stats .stat-card:last-child {
                grid-column:
                    span 2;
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


            .page-header {
                flex-direction: column;
            }


            .add-user-btn {
                width: 100%;

                justify-content: center;
            }


            .user-stats {
                grid-template-columns: 1fr;
            }


            .user-stats .stat-card:last-child {
                grid-column: auto;
            }

        }
    </style>

</head>


<body>


    {{-- =========================================================
        NAVBAR
    ========================================================== --}}

    @include('layouts.navbar-admin')


    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}

    @include('layouts.sidebar-admin')



    {{-- =========================================================
        CONTENT
    ========================================================== --}}

    <main class="admin-content">

        <div class="content">


            {{-- =================================================
                HEADER
            ================================================== --}}

            <div class="page-header">

                <div class="page-title">

                    <h1>
                        Manajemen Pengguna
                    </h1>

                    <p>
                        Kelola akun Admin Utama dan Petugas
                        sistem antrean BPS Kolaka Utara.
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



            {{-- =================================================
                SUCCESS
            ================================================== --}}

            @if (session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

            @endif



            {{-- =================================================
                VALIDATION ERROR
            ================================================== --}}

            @if ($errors->any())

            <div class="alert alert-error">

                Terdapat data yang belum benar.
                Silakan periksa kembali form tambah pengguna.

            </div>

            @endif



            {{-- =================================================
                STATISTIK
            ================================================== --}}

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
                            Admin Utama
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



            {{-- =================================================
                TABLE USER
            ================================================== --}}

            <div class="users-card">

                <div class="card-header">

                    <h2>
                        Daftar Pengguna
                    </h2>


                    <input
                        type="search"
                        class="search-box"
                        id="userSearch"
                        placeholder="Cari nama, username, pelayanan...">

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
                                    PELAYANAN
                                </th>

                                <th>
                                    AKSI
                                </th>

                            </tr>

                        </thead>


                        <tbody id="userTableBody">


                            @forelse ($users as $user)

                            <tr
                                class="user-row"
                                data-search="
                                        {{ strtolower(
                                            $user->name . ' ' .
                                            $user->username . ' ' .
                                            ($user->service->name ?? '')
                                        ) }}
                                    ">

                                {{-- USER --}}

                                <td>

                                    <div class="user-profile">

                                        <div class="avatar">

                                            {{ strtoupper(
                                                    substr(
                                                        $user->name,
                                                        0,
                                                        1
                                                    )
                                                ) }}

                                        </div>


                                        <div>

                                            <div class="user-name">

                                                {{ $user->name }}

                                            </div>

                                            <div class="user-username">

                                                ID #{{ $user->id }}

                                            </div>

                                        </div>

                                    </div>

                                </td>



                                {{-- USERNAME --}}

                                <td>

                                    {{ $user->username }}

                                </td>



                                {{-- ROLE --}}

                                <td>

                                    @if (
                                    $user->role ===
                                    'admin_utama'
                                    )

                                    <span
                                        class="
                                                    role
                                                    admin-utama
                                                ">

                                        Admin Utama

                                    </span>

                                    @else

                                    <span
                                        class="
                                                    role
                                                    petugas
                                                ">

                                        Petugas

                                    </span>

                                    @endif

                                </td>



                                {{-- PELAYANAN --}}

                                <td>

                                    @if (
                                    $user->role ===
                                    'petugas'
                                    &&
                                    $user->service
                                    )

                                    <span
                                        class="service-badge">

                                        {{ $user->service->name }}

                                    </span>

                                    @else

                                    <span
                                        class="service-none">

                                        —

                                    </span>

                                    @endif

                                </td>



                                {{-- AKSI --}}

                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.users.edit', $user->id) }}"
                                            class="action-btn edit"
                                            title="Edit Pengguna">

                                            <svg viewBox="0 0 24 24">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                            </svg>

                                        </a>


                                        <form
                                            action="{{ route('admin.users.destroy', $user->id) }}"
                                            method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete"
                                                title="Hapus Pengguna">

                                                <svg viewBox="0 0 24 24">
                                                    <path d="M3 6h18" />
                                                    <path d="M8 6V4h8v2" />
                                                    <path d="M19 6l-1 14H6L5 6" />
                                                    <path d="M10 11v5" />
                                                    <path d="M14 11v5" />
                                                </svg>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            @empty


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
                                            Tambahkan akun Petugas
                                            untuk mulai menggunakan
                                            sistem.
                                        </p>

                                    </div>

                                </td>

                            </tr>


                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </main>



    {{-- =========================================================
        MODAL TAMBAH USER
    ========================================================== --}}

    <div
        class="modal"
        id="userModal">

        <div class="modal-box">


            {{-- HEADER --}}

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



            {{-- FORM --}}

            <form
                method="POST"
                action="{{ route('admin.users.store') }}"
                id="userForm">

                @csrf


                <div class="modal-body">


                    {{-- =================================================
                        NAMA
                    ================================================== --}}

                    <div class="form-group">

                        <label for="userName">

                            Nama Lengkap

                            <span class="required">
                                *
                            </span>

                        </label>


                        <input
                            type="text"
                            name="name"
                            id="userName"
                            class="
                                form-input
                                @error('name')
                                    input-error
                                @enderror
                            "
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"
                            required>


                        @error('name')

                        <small class="field-error">

                            {{ $message }}

                        </small>

                        @enderror

                    </div>



                    {{-- =================================================
                        USERNAME
                    ================================================== --}}

                    <div class="form-group">

                        <label for="username">

                            Username

                            <span class="required">
                                *
                            </span>

                        </label>


                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="
                                form-input
                                @error('username')
                                    input-error
                                @enderror
                            "
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            minlength="4"
                            maxlength="30"
                            autocomplete="off"
                            required>


                        <small class="field-help">
                            Minimal 4 karakter.
                        </small>


                        @error('username')

                        <small class="field-error">

                            {{ $message }}

                        </small>

                        @enderror

                    </div>



                    {{-- =================================================
                        PASSWORD
                    ================================================== --}}

                    <div class="form-group">

                        <label for="password">

                            Password

                            <span class="required">
                                *
                            </span>

                        </label>


                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="
                                form-input
                                @error('password')
                                    input-error
                                @enderror
                            "
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            autocomplete="new-password"
                            required>


                        <small class="field-help">
                            Password minimal 8 karakter.
                        </small>


                        @error('password')

                        <small class="field-error">

                            {{ $message }}

                        </small>

                        @enderror

                    </div>



                    {{-- =================================================
                        KONFIRMASI PASSWORD
                    ================================================== --}}

                    <div class="form-group">

                        <label for="passwordConfirmation">

                            Konfirmasi Password

                            <span class="required">
                                *
                            </span>

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

                    </div>



                    {{-- =================================================
                        ROLE
                    ================================================== --}}

                    <div class="form-group">

                        <label for="role">

                            Role

                            <span class="required">
                                *
                            </span>

                        </label>


                        <select
                            name="role"
                            id="role"
                            class="
                                form-select
                                @error('role')
                                    input-error
                                @enderror
                            "
                            required>

                            <option value="">
                                Pilih Role
                            </option>


                            <option
                                value="admin_utama"
                                @selected(
                                old('role')==='admin_utama'
                                )>

                                Admin Utama

                            </option>


                            <option
                                value="petugas"
                                @selected(
                                old('role')==='petugas'
                                )>

                                Petugas

                            </option>

                        </select>


                        @error('role')

                        <small class="field-error">

                            {{ $message }}

                        </small>

                        @enderror

                    </div>



                    {{-- =================================================
                        PELAYANAN PETUGAS
                    ================================================== --}}

                    <div
                        class="form-group"
                        id="serviceGroup"
                        @if (
                        old('role')
                        !=='petugas'
                        )
                        hidden
                        @endif>

                        <label for="serviceId">

                            Pelayanan

                            <span class="required">
                                *
                            </span>

                        </label>


                        <div class="service-info">

                            Petugas hanya akan menangani
                            antrean dari pelayanan yang
                            dipilih di bawah ini.

                        </div>


                        <select
                            name="service_id"
                            id="serviceId"
                            class="
                                form-select
                                @error('service_id')
                                    input-error
                                @enderror
                            ">

                            <option value="">
                                Pilih Pelayanan
                            </option>


                            @foreach ($services as $service)

                            <option
                                value="{{ $service->id }}"
                                @selected(
                                old('service_id')==$service->id
                                )
                                >

                                {{ $service->name }}

                            </option>

                            @endforeach

                        </select>


                        @error('service_id')

                        <small class="field-error">

                            {{ $message }}

                        </small>

                        @enderror

                    </div>

                </div>



                {{-- =================================================
                    FOOTER
                ================================================== --}}

                <div class="modal-footer">


                    <button
                        type="button"
                        class="cancel-btn"
                        onclick="closeUserModal()">

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="save-btn">

                        Simpan Pengguna

                    </button>

                </div>

            </form>

        </div>

    </div>



    <script>
        /*
        |--------------------------------------------------------------------------
        | ELEMENT
        |--------------------------------------------------------------------------
        */

        const userModal =
            document.getElementById(
                'userModal'
            );


        const roleSelect =
            document.getElementById(
                'role'
            );


        const serviceGroup =
            document.getElementById(
                'serviceGroup'
            );


        const serviceSelect =
            document.getElementById(
                'serviceId'
            );


        const userSearch =
            document.getElementById(
                'userSearch'
            );



        /*
        |--------------------------------------------------------------------------
        | BUKA MODAL
        |--------------------------------------------------------------------------
        */

        function openUserModal() {

            userModal.classList.add(
                'show'
            );

            document.body.style.overflow =
                'hidden';

        }



        /*
        |--------------------------------------------------------------------------
        | TUTUP MODAL
        |--------------------------------------------------------------------------
        */

        function closeUserModal() {

            userModal.classList.remove(
                'show'
            );

            document.body.style.overflow =
                '';

        }



        /*
        |--------------------------------------------------------------------------
        | ROLE -> PELAYANAN
        |--------------------------------------------------------------------------
        |
        | Petugas:
        | pelayanan ditampilkan dan wajib.
        |
        | Admin:
        | pelayanan disembunyikan.
        |
        */

        function updateServiceField() {

            const isPetugas =
                roleSelect.value ===
                'petugas';


            if (isPetugas) {

                serviceGroup.hidden =
                    false;

                serviceSelect.required =
                    true;

            } else {

                serviceGroup.hidden =
                    true;

                serviceSelect.required =
                    false;

                serviceSelect.value =
                    '';

            }

        }



        roleSelect.addEventListener(
            'change',
            updateServiceField
        );


        updateServiceField();



        /*
        |--------------------------------------------------------------------------
        | KLIK LUAR MODAL
        |--------------------------------------------------------------------------
        */

        userModal.addEventListener(
            'click',
            function(event) {

                if (
                    event.target ===
                    userModal
                ) {

                    closeUserModal();

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function(event) {

                if (
                    event.key ===
                    'Escape'
                ) {

                    closeUserModal();

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | SEARCH USER
        |--------------------------------------------------------------------------
        */

        if (userSearch) {

            userSearch.addEventListener(
                'input',
                function() {

                    const keyword =
                        this.value
                        .toLowerCase()
                        .trim();


                    const rows =
                        document.querySelectorAll(
                            '.user-row'
                        );


                    rows.forEach(
                        function(row) {

                            const text =
                                row.dataset.search
                                .toLowerCase();


                            row.style.display =
                                text.includes(
                                    keyword
                                ) ?
                                '' :
                                'none';

                        }
                    );

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | JIKA VALIDASI SERVER GAGAL
        |--------------------------------------------------------------------------
        |
        | Modal otomatis dibuka kembali.
        |
        */
    </script>


</body>

</html>