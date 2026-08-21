@extends('layouts.admin')

@section('title', 'Edit Pengguna - Sistem Antrian BPS')

@push('styles')
<style>
    /* =========================================================
       HALAMAN EDIT PENGGUNA
    ========================================================= */

    .edit-user-page {
        padding: 28px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 24px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        color: #111827;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }


    /* =========================================================
       CARD
    ========================================================= */

    .edit-user-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }


    /* =========================================================
       CARD HEADER
    ========================================================= */

    .edit-card-header {
        padding: 22px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .edit-card-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .edit-card-header p {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 13px;
    }


    /* =========================================================
       FORM
    ========================================================= */

    .edit-form {
        padding: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .form-label span {
        color: #ef4444;
    }


    /* =========================================================
       INPUT
    ========================================================= */

    .form-input,
    .form-select {
        width: 100%;
        height: 44px;

        padding: 0 13px;

        border: 1px solid #d1d5db;
        border-radius: 8px;

        background: #ffffff;

        color: #111827;

        font-family: inherit;
        font-size: 14px;

        outline: none;

        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: #2563eb;

        box-shadow:
            0 0 0 3px rgba(37, 99, 235, .10);
    }

    .form-input::placeholder {
        color: #9ca3af;
    }


    /* =========================================================
       PASSWORD INFO
    ========================================================= */

    .input-help {
        margin: 0;

        color: #6b7280;

        font-size: 12px;
        line-height: 1.5;
    }


    /* =========================================================
       ERROR
    ========================================================= */

    .form-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 2px;
    }

    .alert-error {
        margin-bottom: 20px;

        padding: 13px 15px;

        border: 1px solid #fecaca;
        border-radius: 8px;

        background: #fef2f2;
        color: #b91c1c;

        font-size: 13px;
    }


    /* =========================================================
       PELAYANAN
    ========================================================= */

    #serviceGroup {
        transition: opacity .2s ease;
    }

    #serviceGroup.hidden {
        display: none;
    }


    /* =========================================================
       FOOTER
    ========================================================= */

    .edit-form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        gap: 10px;

        margin-top: 28px;
        padding-top: 20px;

        border-top: 1px solid #e5e7eb;
    }


    /* =========================================================
       BUTTON
    ========================================================= */

    .cancel-btn,
    .save-btn {
        min-height: 42px;

        padding: 0 18px;

        border-radius: 8px;

        font-family: inherit;
        font-size: 13px;
        font-weight: 600;

        cursor: pointer;

        transition:
            background-color .2s ease,
            border-color .2s ease,
            transform .15s ease;
    }

    .cancel-btn {
        color: #374151;

        background: #ffffff;

        border: 1px solid #d1d5db;

        text-decoration: none;

        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .cancel-btn:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .save-btn {
        color: #ffffff;

        background: #2563eb;

        border: 1px solid #2563eb;
    }

    .save-btn:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .cancel-btn:active,
    .save-btn:active {
        transform: scale(.98);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .edit-user-page {
            padding: 18px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }

        .edit-form {
            padding: 18px;
        }

        .edit-form-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
        }

    }
</style>
@endpush


@section('content')

<div class="edit-user-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="page-header">

        <h1>
            Edit Pengguna
        </h1>

        <p>
            Perbarui informasi akun pengguna sistem antrian BPS.
        </p>

    </div>



    {{-- =====================================================
         ERROR VALIDASI
    ====================================================== --}}

    @if ($errors->any())

    <div class="alert-error">

        <strong>
            Terdapat kesalahan:
        </strong>

        <ul style="margin: 8px 0 0 18px;">

            @foreach ($errors->all() as $error)

            <li>
                {{ $error }}
            </li>

            @endforeach

        </ul>

    </div>

    @endif



    {{-- =====================================================
         CARD
    ====================================================== --}}

    <div class="edit-user-card">


        {{-- HEADER CARD --}}

        <div class="edit-card-header">

            <h2>
                Informasi Pengguna
            </h2>

            <p>
                Edit data akun
                <strong>{{ $user->name }}</strong>.
            </p>

        </div>



        {{-- FORM --}}

        <form
            action="{{ route('admin.users.update', $user->id) }}"
            method="POST"
            class="edit-form">

            @csrf

            @method('PUT')


            <div class="form-grid">


                {{-- NAMA --}}

                <div class="form-group">

                    <label
                        for="name"
                        class="form-label">
                        Nama Lengkap
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-input"
                        value="{{ old('name', $user->name) }}"
                        placeholder="Masukkan nama lengkap"
                        required>

                    @error('name')

                    <div class="form-error">
                        {{ $message }}
                    </div>

                    @enderror

                </div>



                {{-- USERNAME --}}

                <div class="form-group">

                    <label
                        for="username"
                        class="form-label">
                        Username
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-input"
                        value="{{ old('username', $user->username) }}"
                        placeholder="Masukkan username"
                        required>

                    @error('username')

                    <div class="form-error">
                        {{ $message }}
                    </div>

                    @enderror

                </div>



                {{-- ROLE --}}

                <div class="form-group">

                    <label
                        for="role"
                        class="form-label">
                        Role
                        <span>*</span>
                    </label>

                    <select
                        id="role"
                        name="role"
                        class="form-select"
                        required>

                        <option
                            value="admin_utama"
                            {{ old('role', $user->role) === 'admin_utama' ? 'selected' : '' }}>
                            Admin Utama
                        </option>

                        <option
                            value="petugas"
                            {{ old('role', $user->role) === 'petugas' ? 'selected' : '' }}>
                            Petugas
                        </option>

                    </select>

                    @error('role')

                    <div class="form-error">
                        {{ $message }}
                    </div>

                    @enderror

                </div>



                {{-- PELAYANAN --}}

                <div
                    class="form-group"
                    id="serviceGroup">

                    <label
                        for="service_id"
                        class="form-label">
                        Pelayanan

                        <span>*</span>

                    </label>


                    <select
                        id="service_id"
                        name="service_id"
                        class="form-select">

                        <option value="">
                            Pilih pelayanan
                        </option>


                        @foreach ($services as $service)

                        <option
                            value="{{ $service->id }}"
                            {{ old('service_id', $user->service_id) == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>

                        @endforeach

                    </select>


                    @error('service_id')

                    <div class="form-error">
                        {{ $message }}
                    </div>

                    @enderror

                </div>



                {{-- PASSWORD BARU --}}

                <div class="form-group">

                    <label
                        for="password"
                        class="form-label">
                        Password Baru
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Kosongkan jika tidak ingin mengubah">

                    <p class="input-help">
                        Kosongkan jika password tidak ingin diubah.
                    </p>


                    @error('password')

                    <div class="form-error">
                        {{ $message }}
                    </div>

                    @enderror

                </div>



                {{-- KONFIRMASI PASSWORD --}}

                <div class="form-group">

                    <label
                        for="password_confirmation"
                        class="form-label">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-input"
                        placeholder="Ulangi password baru">

                </div>

            </div>



            {{-- FOOTER --}}

            <div class="edit-form-footer">

                <a
                    href="{{ route('admin.users') }}"
                    class="cancel-btn">
                    Batal
                </a>


                <button
                    type="submit"
                    class="save-btn">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


<script>
    const roleSelect =
        document.getElementById('role');

    const serviceGroup =
        document.getElementById('serviceGroup');

    const serviceSelect =
        document.getElementById('service_id');


    function updateServiceField() {

        if (
            roleSelect.value === 'petugas'
        ) {

            serviceGroup.classList.remove(
                'hidden'
            );

            serviceSelect.required = true;

        } else {

            serviceGroup.classList.add(
                'hidden'
            );

            serviceSelect.required = false;

            serviceSelect.value = '';

        }

    }


    roleSelect.addEventListener(
        'change',
        updateServiceField
    );


    updateServiceField();
</script>

@endsection