<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    .petugas-content {

    @vite('resources/css/layouts/sidebar-petugas.css')

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fc;
            color: #1f2937;
        }


        /* ========================================
           CONTENT UTAMA
        ======================================== */

        .petugas-content {
            margin-left: 250px;
            padding: 80px 35px 50px;
            min-height: 100vh;
        }


        /* ========================================
           HEADER HALAMAN
        ======================================== */

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title h1 {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
            color: #173f8f;
        }

        .page-title p {
            margin: 7px 0 0;
            font-size: 14px;
            color: #718096;
        }


        /* ========================================
           KARTU INFORMASI
        ======================================== */

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .info-card {
            background: #ffffff;
            border: 1px solid #e5eaf2;
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 3px 12px rgba(25, 55, 100, 0.05);
        }

        .info-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #edf3ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 23px;
        }

        .info-text span {
            display: block;
            font-size: 13px;
            color: #718096;
            margin-bottom: 5px;
        }

        .info-text strong {
            display: block;
            font-size: 21px;
            color: #173f8f;
        }


        /* ========================================
           CARD JADWAL
        ======================================== */

        .schedule-card {
            background: #ffffff;
            border: 1px solid #e5eaf2;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(25, 55, 100, 0.06);
        }


        /* HEADER CARD */

        .schedule-header {
            padding: 22px 24px;
            border-bottom: 1px solid #edf0f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .schedule-title h2 {
            margin: 0;
            color: #173f8f;
            font-size: 20px;
        }

        .schedule-title p {
            margin: 5px 0 0;
            color: #8a94a6;
            font-size: 13px;
        }

        .schedule-count {
            background: #edf3ff;
            color: #2451ad;
            border: 1px solid #d8e4ff;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }


        /* ========================================
           TABEL
        ======================================== */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }

        .schedule-table th {
            padding: 14px 18px;
            background: #f8faff;
            color: #52617a;
            font-size: 12px;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: .4px;
            border-bottom: 1px solid #e8edf4;
            white-space: nowrap;
        }

        .schedule-table td {
            padding: 17px 18px;
            font-size: 14px;
            color: #39465b;
            border-bottom: 1px solid #edf0f5;
            vertical-align: middle;
        }

        .schedule-table tbody tr {
            transition: background .2s ease;
        }

        .schedule-table tbody tr:hover {
            background: #f8faff;
        }

        .schedule-table tbody tr:last-child td {
            border-bottom: none;
        }


        /* NOMOR */

        .number {
            width: 55px;
            color: #8490a5;
            font-weight: 600;
        }


        /* TANGGAL */

        .date-main {
            font-weight: 600;
            color: #263b62;
        }


        /* JAM */

        .time-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .time {
            font-weight: 600;
            color: #263b62;
        }

        .time-separator {
            color: #a0a9b8;
        }


        /* STATUS */

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 12px;
            border-radius: 20px;
            background: #eaf8ef;
            color: #16803c;
            font-size: 12px;
            font-weight: 700;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            background: #20b95b;
            border-radius: 50%;
        }


        /* KETERANGAN */

        .notes {
            color: #68758a;
        }


        /* ========================================
           EMPTY STATE
        ======================================== */

        .empty {
            padding: 65px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 15px;
            border-radius: 16px;
            background: #f1f5fb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .empty h3 {
            margin: 0;
            font-size: 17px;
            color: #36445a;
        }

        .empty p {
            margin: 7px 0 0;
            color: #8994a6;
            font-size: 13px;
        }


        /* ========================================
           RESPONSIVE
        ======================================== */

        @media (max-width: 1000px) {

            .info-grid {
                grid-template-columns: 1fr;
            }

            .petugas-content {
                padding: 25px;
            }

        }


        @media (max-width: 768px) {

            .petugas-content {
                margin-left: 0;
                padding: 20px 15px 40px;
            }

            .page-header {
                align-items: flex-start;
            }

            .page-title h1 {
                font-size: 25px;
            }

            .schedule-header {
                padding: 18px;
            }

            .schedule-table th,
            .schedule-table td {
                padding: 13px;
            }

        }
    </style>

</head>


<body>


    {{-- ==========================================
         NAVBAR PETUGAS
    =========================================== --}}

    @include('layouts.navbar-petugas')


    {{-- ==========================================
         SIDEBAR PETUGAS
    =========================================== --}}

    @include('layouts.sidebar-petugas')


    {{-- ==========================================
         CONTENT
    =========================================== --}}

    <main class="petugas-content">


        {{-- HEADER HALAMAN --}}

        <div class="page-header">

            <div class="page-title">

                <h1>
                    Jadwal Saya
                </h1>

                <p>
                    Lihat jadwal pelayanan Anda sebagai petugas BPS Kolaka Utara.
                </p>

            </div>

        </div>


        {{-- ==========================================
             INFO CARD
        =========================================== --}}

        @php
        $totalJadwal = $jadwals->count();

        $jadwalHariIni = $jadwals->filter(function ($jadwal) {
        return $jadwal->date &&
        $jadwal->date->isToday();
        })->count();

        $jadwalAktif = $jadwals->where('status', 'aktif')->count();
        @endphp


        <div class="info-grid">


            {{-- TOTAL JADWAL --}}

            <div class="info-card">

                <div class="info-icon">
                    📅
                </div>

                <div class="info-text">

                    <span>
                        Total Jadwal
                    </span>

                    <strong>
                        {{ $totalJadwal }}
                    </strong>

                </div>

            </div>


            {{-- JADWAL HARI INI --}}

            <div class="info-card">

                <div class="info-icon">
                    🗓️
                </div>

                <div class="info-text">

                    <span>
                        Jadwal Hari Ini
                    </span>

                    <strong>
                        {{ $jadwalHariIni }}
                    </strong>

                </div>

            </div>


            {{-- JADWAL AKTIF --}}

            <div class="info-card">

                <div class="info-icon">
                    ✓
                </div>

                <div class="info-text">

                    <span>
                        Jadwal Aktif
                    </span>

                    <strong>
                        {{ $jadwalAktif }}
                    </strong>

                </div>

            </div>


        </div>


        {{-- ==========================================
             DAFTAR JADWAL
        =========================================== --}}

        <div class="schedule-card">


            {{-- HEADER CARD --}}

            <div class="schedule-header">

                <div class="schedule-title">

                    <h2>
                        Daftar Jadwal
                    </h2>

                    <p>
                        Jadwal pelayanan yang diberikan kepada Anda.
                    </p>

                </div>


                <div class="schedule-count">

                    {{ $totalJadwal }} Jadwal

                </div>

            </div>


            @if($jadwals->count() > 0)


            {{-- TABLE --}}

            <div class="table-wrapper">

                <table class="schedule-table">

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Waktu Pelayanan
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Keterangan
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($jadwals as $jadwal)

                        <tr>


                            {{-- NOMOR --}}

                            <td class="number">

                                {{ $loop->iteration }}

                            </td>


                            {{-- TANGGAL --}}

                            <td>

                                <div class="date-main">

                                    {{ $jadwal->date?->format('d/m/Y') ?? '-' }}

                                </div>

                            </td>


                            {{-- WAKTU --}}

                            <td>

                                <div class="time-wrapper">

                                    <span class="time">

                                        {{ $jadwal->start_time
                                                    ? \Carbon\Carbon::parse($jadwal->start_time)->format('H:i')
                                                    : '-' }}

                                    </span>

                                    <span class="time-separator">
                                        —
                                    </span>

                                    <span class="time">

                                        {{ $jadwal->end_time
                                                    ? \Carbon\Carbon::parse($jadwal->end_time)->format('H:i')
                                                    : '-' }}

                                    </span>

                                </div>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                <span class="status">

                                    <span class="status-dot"></span>

                                    {{ ucfirst($jadwal->status ?? 'Aktif') }}

                                </span>

                            </td>


                            {{-- KETERANGAN --}}

                            <td class="notes">

                                {{ $jadwal->notes ?? '-' }}

                            </td>


                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            @else


            {{-- EMPTY STATE --}}

            <div class="empty">

                <div class="empty-icon">
                    📅
                </div>

                <h3>
                    Belum Ada Jadwal
                </h3>

                <p>
                    Saat ini belum ada jadwal pelayanan yang diberikan kepada Anda.
                </p>

            </div>


            @endif


        </div>


    </main>


</body>

</html>