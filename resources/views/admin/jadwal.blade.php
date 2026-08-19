@extends('layouts.admin')

@push('styles')
@vite('resources/css/admin/jadwal.css')
@endpush

@section('content')
<div class="jadwal-page">

    <!-- HEADER -->
    <div class="page-header">

        <div>
            <h1>Jadwal Petugas</h1>

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


    <!-- FILTER -->
    <div class="filter-card">

        <div class="filter-group">

            <label>Bulan</label>

            <select class="filter-input">

                <option>Agustus 2026</option>
                <option>September 2026</option>
                <option>Oktober 2026</option>

            </select>

        </div>


        <div class="filter-group">

            <label>Petugas</label>

            <select class="filter-input">

                <option>Semua Petugas</option>
                <option>Petugas 1</option>
                <option>Petugas 2</option>

            </select>

        </div>

        <button
            type="button"
            class="btn-filter">

            🔎 Filter

        </button>

    </div>


    <!-- STATISTIK -->
    <div class="stats-grid">

        <div class="stat-card">

            <div>

                <div class="stat-title">
                    Total Jadwal
                </div>

                <div class="stat-number">
                    0
                </div>

                <div class="stat-desc">
                    Jadwal bulan ini
                </div>

            </div>

            <div class="stat-icon">
                📅
            </div>

        </div>


        <div class="stat-card">

            <div>

                <div class="stat-title">
                    Petugas Bertugas
                </div>

                <div class="stat-number">
                    0
                </div>

                <div class="stat-desc">
                    Petugas aktif
                </div>

            </div>

            <div class="stat-icon">
                👨‍💼
            </div>

        </div>


        <div class="stat-card">

            <div>

                <div class="stat-title">
                    Hari Ini
                </div>

                <div class="stat-number">
                    0
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


    <!-- TABEL -->
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

                        <th>TANGGAL</th>
                        <th>PETUGAS</th>
                        <th>LAYANAN</th>
                        <th>JAM</th>
                        <th>STATUS</th>
                        <th>AKSI</th>

                    </tr>

                </thead>


                <tbody>

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

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- MODAL TAMBAH JADWAL -->

<div
    class="modal"
    id="jadwalModal">

    <div class="modal-box">


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


        <div class="modal-body">


            <div class="form-group">

                <label>
                    Petugas
                </label>

                <select class="form-input">

                    <option value="">
                        Pilih Petugas
                    </option>

                    <option>
                        Petugas 1
                    </option>

                    <option>
                        Petugas 2
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Tanggal
                </label>

                <input
                    type="date"
                    class="form-input">

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label>
                        Jam Mulai
                    </label>

                    <input
                        type="time"
                        class="form-input"
                        value="08:00">

                </div>


                <div class="form-group">

                    <label>
                        Jam Selesai
                    </label>

                    <input
                        type="time"
                        class="form-input"
                        value="16:00">

                </div>

            </div>


            <div class="form-group">

                <label>
                    Layanan
                </label>

                <select class="form-input">

                    <option value="">
                        Pilih Layanan
                    </option>

                    <option>
                        Pelayanan Perpustakaan
                    </option>

                    <option>
                        Pelayanan Konsultasi
                    </option>

                    <option>
                        Penjualan Produk Statistik
                    </option>

                    <option>
                        Pelayanan Rekomendasi
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Keterangan
                </label>

                <textarea
                    class="form-input"
                    rows="3"
                    placeholder="Keterangan tambahan (opsional)"></textarea>

            </div>

        </div>


        <div class="modal-footer">

            <button
                type="button"
                class="btn-cancel"
                onclick="closeJadwalModal()">

                Batal

            </button>


            <button
                type="button"
                class="btn-primary">

                Simpan Jadwal

            </button>

        </div>

    </div>

</div>


<!-- JAVASCRIPT -->

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


    document
        .getElementById('jadwalModal')
        .addEventListener(
            'click',
            function(event) {
                if (event.target === this) {

                    closeJadwalModal();

                }
            }
        );
</script>


@endsection