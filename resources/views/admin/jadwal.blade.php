@extends('layouts.admin')

@push('styles')
@vite('resources/css/admin/jadwal.css')
@endpush


@section('content')

<div class="jadwal-page">


    {{-- =====================================
     POPUP VALIDASI
===================================== --}}

    @if (session('success'))

    <div
        class="validation-popup success"
        id="successPopup">

        <div class="popup-icon">
            ✓
        </div>

        <div class="popup-title">
            Berhasil
        </div>

        <div class="popup-message">
            {{ session('success') }}
        </div>

    </div>

    @endif


    @if ($errors->any())

    <div
        class="validation-popup error"
        id="errorPopup">

        <div class="popup-icon">
            !
        </div>

        <div class="popup-title">
            Gagal
        </div>

        <div class="popup-message">
            {{ $errors->first() }}
        </div>

    </div>

    @endif



    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="page-header">

        <div>

            <h1>
                Jadwal Petugas
            </h1>

            <p>
                Kelola jadwal piket dan penempatan petugas
                pelayanan BPS Kolaka Utara.
            </p>

        </div>


        <button
            type="button"
            class="btn-primary"
            onclick="openJadwalModal()">

            ＋ Tambah Jadwal

        </button>

    </div>



    {{-- =========================================================
         FILTER
    ========================================================== --}}

    <div class="filter-card">


        <div class="filter-group">

            <label>
                Bulan
            </label>

            <select
                class="filter-input"
                id="filterBulan">

                <option value="">
                    Semua Bulan
                </option>

                <option value="08">
                    Agustus 2026
                </option>

                <option value="09">
                    September 2026
                </option>

                <option value="10">
                    Oktober 2026
                </option>

            </select>

        </div>



        <div class="filter-group">

            <label>
                Petugas
            </label>

            <select
                class="filter-input"
                id="filterPetugas">

                <option value="">
                    Semua Petugas
                </option>

                @foreach ($petugas as $user)

                <option value="{{ $user->id }}">
                    {{ $user->name }}
                </option>

                @endforeach

            </select>

        </div>


        <button
            type="button"
            class="btn-filter">

            🔎 Filter

        </button>

    </div>



    {{-- =========================================================
         STATISTIK
    ========================================================== --}}

    <div class="stats-grid">


        {{-- TOTAL JADWAL --}}

        <div class="stat-card">

            <div>

                <div class="stat-title">
                    Total Jadwal
                </div>

                <div class="stat-number">
                    {{ $schedules->count() }}
                </div>

                <div class="stat-desc">
                    Total jadwal
                </div>

            </div>

            <div class="stat-icon">
                📅
            </div>

        </div>



        {{-- PETUGAS BERTUGAS --}}

        <div class="stat-card">

            <div>

                <div class="stat-title">
                    Petugas Bertugas
                </div>

                <div class="stat-number">
                    {{ $schedules->pluck('user_id')->unique()->count() }}
                </div>

                <div class="stat-desc">
                    Petugas memiliki jadwal
                </div>

            </div>

            <div class="stat-icon">
                👨‍💼
            </div>

        </div>



        {{-- HARI INI --}}

        <div class="stat-card">

            <div>

                <div class="stat-title">
                    Hari Ini
                </div>

                <div class="stat-number">

                    {{ $schedules->where(
                        'date',
                        now()->format('Y-m-d')
                    )->count() }}

                </div>

                <div class="stat-desc">
                    Petugas piket hari ini
                </div>

            </div>

            <div class="stat-icon">
                🕐
            </div>

        </div>

    </div>



    {{-- =========================================================
         TABEL
    ========================================================== --}}

    <div class="table-card">


        <div class="table-header">

            <div>

                <h2>
                    Daftar Jadwal Petugas
                </h2>

                <p>
                    Jadwal piket petugas pelayanan
                </p>

            </div>

        </div>



        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            TANGGAL
                        </th>

                        <th>
                            PETUGAS
                        </th>

                        <th>
                            JAM
                        </th>

                        <th>
                            KETERANGAN
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


                    @forelse ($schedules as $schedule)

                    <tr>


                        {{-- TANGGAL --}}

                        <td>

                            {{ $schedule->date->format('d/m/Y') }}

                        </td>



                        {{-- PETUGAS --}}

                        <td>

                            <div class="user-info">
                                <span>

                                    {{ $schedule->user->name }}

                                </span>

                            </div>

                        </td>



                        {{-- JAM --}}

                        <td>

                            {{ substr($schedule->start_time, 0, 5) }}

                            -

                            {{ substr($schedule->end_time, 0, 5) }}

                        </td>



                        {{-- KETERANGAN --}}

                        <td>

                            {{ $schedule->notes ?: '-' }}

                        </td>



                        {{-- STATUS --}}

                        <td>

                            @if ($schedule->status === 'aktif')

                            <span class="status-badge active">
                                ● Aktif
                            </span>

                            @elseif ($schedule->status === 'selesai')

                            <span class="status-badge selesai">
                                ● Selesai
                            </span>

                            @else

                            <span class="status-badge inactive">
                                ● Dibatalkan
                            </span>

                            @endif

                        </td>



                        {{-- AKSI --}}

                        <td>

                            <div class="action-buttons">


                                <a
                                    href="{{ route(
        'admin.jadwal.edit',
        $schedule->id
    ) }}"
                                    class="action-btn edit"
                                    title="Edit Jadwal">

                                    <svg
                                        viewBox="0 0 24 24"
                                        aria-hidden="true">

                                        <path d="M12 20h9" />

                                        <path
                                            d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z" />

                                    </svg>

                                </a>


                                <form
                                    method="POST"
                                    action="{{ route(
        'admin.jadwal.destroy',
        $schedule->id
    ) }}"
                                    style="display:inline;"
                                    onsubmit="return confirm(
        'Apakah Anda yakin ingin menghapus jadwal ini?'
    );">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="action-btn delete"
                                        title="Hapus Jadwal">

                                        <svg
                                            viewBox="0 0 24 24"
                                            aria-hidden="true">

                                            <path d="M3 6h18" />

                                            <path d="M8 6V4h8v2" />

                                            <path d="M19 6l-1 15H6L5 6" />

                                            <path d="M10 11v6" />

                                            <path d="M14 11v6" />

                                        </svg>

                                    </button>

                                </form>


                            </div>

                        </td>

                    </tr>


                    @empty


                    <tr>

                        <td colspan="6">

                            <div class="empty-state">

                                <div class="empty-icon">
                                    📅
                                </div>

                                <h3>
                                    Belum Ada Jadwal
                                </h3>

                                <p>
                                    Tambahkan jadwal petugas
                                    untuk mulai mengatur piket.
                                </p>


                                <button
                                    type="button"
                                    class="btn-primary"
                                    onclick="openJadwalModal()">

                                    ＋ Tambah Jadwal

                                </button>

                            </div>

                        </td>

                    </tr>


                    @endforelse


                </tbody>

            </table>

        </div>

    </div>

</div>



{{-- =============================================================
     MODAL TAMBAH JADWAL
============================================================= --}}

<div
    class="modal"
    id="jadwalModal">

    <div class="modal-box">


        {{-- HEADER MODAL --}}

        <div class="modal-header">

            <div>

                <h2>
                    Tambah Jadwal Petugas
                </h2>

                <p>
                    Masukkan jadwal piket petugas.
                </p>

            </div>


            <button
                type="button"
                class="close-btn"
                onclick="closeJadwalModal()">

                ×

            </button>

        </div>



        {{-- =====================================================
             FORM
        ====================================================== --}}

        <form
            method="POST"
            action="{{ route('admin.jadwal.store') }}">

            @csrf


            <div class="modal-body">


                {{-- PETUGAS --}}

                <div class="form-group">

                    <label>
                        Petugas
                    </label>


                    <select
                        name="user_id"
                        class="form-input"
                        required>

                        <option value="">
                            Pilih Petugas
                        </option>


                        @foreach ($petugas as $user)

                        <option
                            value="{{ $user->id }}"
                            {{ old('user_id') == $user->id ? 'selected' : '' }}>

                            {{ $user->name }}

                        </option>

                        @endforeach

                    </select>


                    @error('user_id')

                    <small class="error-message">
                        {{ $message }}
                    </small>

                    @enderror

                </div>



                {{-- TANGGAL --}}

                <div class="form-group">

                    <label>
                        Tanggal
                    </label>


                    <input
                        type="date"
                        name="date"
                        class="form-input"
                        value="{{ old('date', date('Y-m-d')) }}"
                        required>


                    @error('date')

                    <small class="error-message">
                        {{ $message }}
                    </small>

                    @enderror

                </div>



                {{-- JAM --}}

                <div class="form-row">


                    <div class="form-group">

                        <label>
                            Jam Mulai
                        </label>


                        <input
                            type="time"
                            name="start_time"
                            class="form-input"
                            value="{{ old('start_time', '08:00') }}"
                            required>


                        @error('start_time')

                        <small class="error-message">
                            {{ $message }}
                        </small>

                        @enderror

                    </div>



                    <div class="form-group">

                        <label>
                            Jam Selesai
                        </label>


                        <input
                            type="time"
                            name="end_time"
                            class="form-input"
                            value="{{ old('end_time', '16:00') }}"
                            required>


                        @error('end_time')

                        <small class="error-message">
                            {{ $message }}
                        </small>

                        @enderror

                    </div>

                </div>



                {{-- KETERANGAN --}}

                <div class="form-group">

                    <label>
                        Keterangan
                    </label>


                    <textarea
                        name="notes"
                        class="form-input"
                        rows="3"
                        placeholder="Keterangan tambahan (opsional)">{{ old('notes') }}</textarea>


                    @error('notes')

                    <small class="error-message">
                        {{ $message }}
                    </small>

                    @enderror

                </div>


            </div>



            {{-- FOOTER MODAL --}}

            <div class="modal-footer">


                <button
                    type="button"
                    class="btn-cancel"
                    onclick="closeJadwalModal()">

                    Batal

                </button>


                <button
                    type="submit"
                    class="btn-primary">

                    Simpan Jadwal

                </button>


            </div>


        </form>

    </div>

</div>



{{-- =============================================================
     JAVASCRIPT
============================================================= --}}

<script>
    function openJadwalModal() {

        document
            .getElementById('jadwalModal')
            .classList
            .add('show');

    }


    function closeJadwalModal() {

        document
            .getElementById('jadwalModal')
            .classList
            .remove('show');

    }


    const jadwalModal =
        document.getElementById('jadwalModal');


    if (jadwalModal) {

        jadwalModal.addEventListener(
            'click',
            function(event) {

                if (event.target === this) {

                    closeJadwalModal();

                }

            }
        );

    }
</script>
<script>
    document.addEventListener(
        'DOMContentLoaded',
        function() {

            const successPopup =
                document.getElementById(
                    'successPopup'
                );

            const errorPopup =
                document.getElementById(
                    'errorPopup'
                );


            /*
            |--------------------------------------------------------------------------
            | BERHASIL
            |--------------------------------------------------------------------------
            */

            if (successPopup) {

                setTimeout(function() {

                    closeValidationPopup(
                        successPopup
                    );

                }, 3000);

            }


            /*
            |--------------------------------------------------------------------------
            | ERROR
            |--------------------------------------------------------------------------
            */

            if (errorPopup) {

                setTimeout(function() {

                    closeValidationPopup(
                        errorPopup
                    );

                }, 3000);

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CLOSE POPUP
    |--------------------------------------------------------------------------
    */

    function closeValidationPopup(popup) {

        if (!popup) {
            return;
        }


        popup.style.animation =
            'popupHide .25s ease forwards';


        setTimeout(function() {

            popup.remove();

        }, 250);

    }
</script>



@endsection