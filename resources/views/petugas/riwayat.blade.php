<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Layanan - Petugas</title>

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


        /* CONTENT */

        .petugas-content {
            margin-left: 250px;
            padding: 105px 35px 50px;
            min-height: 100vh;
        }


        /* HEADER */

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0;
            color: #173f8f;
            font-size: 30px;
            font-weight: 700;
        }

        .page-header p {
            margin: 7px 0 0;
            color: #718096;
            font-size: 14px;
        }


        /* STATISTIC */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e5eaf2;
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 3px 12px rgba(25, 55, 100, 0.05);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #edf3ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-info span {
            display: block;
            color: #718096;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .stat-info strong {
            display: block;
            color: #173f8f;
            font-size: 22px;
        }


        /* MAIN CARD */

        .history-card {
            background: #ffffff;
            border: 1px solid #e5eaf2;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(25, 55, 100, 0.06);
        }


        /* CARD HEADER */

        .history-header {
            padding: 22px 24px;
            border-bottom: 1px solid #edf0f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-title h2 {
            margin: 0;
            color: #173f8f;
            font-size: 20px;
        }

        .history-title p {
            margin: 5px 0 0;
            color: #8994a6;
            font-size: 13px;
        }

        .history-count {
            background: #edf3ff;
            border: 1px solid #d8e4ff;
            color: #2451ad;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }


        /* TABLE */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th {
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

        .history-table td {
            padding: 16px 18px;
            font-size: 14px;
            color: #39465b;
            border-bottom: 1px solid #edf0f5;
            vertical-align: middle;
        }

        .history-table tbody tr {
            transition: background .2s ease;
        }

        .history-table tbody tr:hover {
            background: #f8faff;
        }

        .history-table tbody tr:last-child td {
            border-bottom: none;
        }


        /* QUEUE NUMBER */

        .queue-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 70px;
            padding: 7px 12px;
            background: #edf3ff;
            border: 1px solid #d8e4ff;
            border-radius: 8px;
            color: #2451ad;
            font-weight: 700;
        }


        /* SERVICE */

        .service-name {
            font-weight: 600;
            color: #263b62;
        }


        /* TIME */

        .time {
            color: #4b5870;
            font-weight: 600;
        }


        /* STATUS */

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .status.completed {
            background: #eaf8ef;
            color: #16803c;
        }

        .status.skipped {
            background: #fff2e8;
            color: #d96a0b;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        .status.completed .status-dot {
            background: #20b95b;
        }

        .status.skipped .status-dot {
            background: #f28a2e;
        }


        /* EMPTY */

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


        /* RESPONSIVE */

        @media (max-width: 1000px) {

            .stats-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 768px) {

            .petugas-content {
                margin-left: 0;
                padding: 90px 15px 40px;
            }

            .page-header h1 {
                font-size: 25px;
            }

            .history-header {
                padding: 18px;
            }

            .history-table th,
            .history-table td {
                padding: 13px;
            }

        }
    </style>

</head>


<body>


    {{-- NAVBAR PETUGAS --}}

    @include('layouts.navbar-petugas')


    {{-- SIDEBAR PETUGAS --}}

    @include('layouts.sidebar-petugas')


    {{-- CONTENT --}}

    <main class="petugas-content">


        {{-- HEADER --}}

        <div class="page-header">

            <h1>
                Riwayat Layanan
            </h1>

            <p>
                Riwayat pelayanan antrean yang telah Anda tangani.
            </p>

        </div>


        {{-- STATISTIK --}}

        <div class="stats-grid">


            <div class="stat-card">

                <div class="stat-icon">
                    📊
                </div>

                <div class="stat-info">

                    <span>
                        Total Riwayat
                    </span>

                    <strong>
                        {{ $totalRiwayat }}
                    </strong>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    ✓
                </div>

                <div class="stat-info">

                    <span>
                        Selesai
                    </span>

                    <strong>
                        {{ $totalSelesai }}
                    </strong>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    ↷
                </div>

                <div class="stat-info">

                    <span>
                        Dilewati
                    </span>

                    <strong>
                        {{ $totalDilewati }}
                    </strong>

                </div>

            </div>


        </div>


        {{-- RIWAYAT --}}

        <div class="history-card">


            <div class="history-header">

                <div class="history-title">

                    <h2>
                        Riwayat Pelayanan
                    </h2>

                    <p>
                        Daftar antrean yang telah Anda tangani.
                    </p>

                </div>


                <div class="history-count">

                    {{ $totalRiwayat }} Riwayat

                </div>

            </div>


            @if($riwayat->count() > 0)

            <div class="table-wrapper">

                <table class="history-table">

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Nomor Antrean
                            </th>

                            <th>
                                Layanan
                            </th>

                            <th>
                                Dipanggil
                            </th>

                            <th>
                                Selesai
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($riwayat as $item)

                        <tr>


                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>

                                <span class="queue-number">
                                    {{ $item->queue_number }}
                                </span>

                            </td>


                            <td>

                                <span class="service-name">

                                    {{ $item->service?->name ?? '-' }}

                                </span>

                            </td>


                            <td>

                                <span class="time">

                                    {{ $item->called_at
                                                ? \Carbon\Carbon::parse($item->called_at)->format('d/m/Y H:i')
                                                : '-' }}

                                </span>

                            </td>


                            <td>

                                <span class="time">

                                    {{ $item->completed_at
                                                ? \Carbon\Carbon::parse($item->completed_at)->format('d/m/Y H:i')
                                                : '-' }}

                                </span>

                            </td>


                            <td>

                                @if($item->status === 'completed')

                                <span class="status completed">

                                    <span class="status-dot"></span>

                                    Selesai

                                </span>

                                @elseif($item->status === 'skipped')

                                <span class="status skipped">

                                    <span class="status-dot"></span>

                                    Dilewati

                                </span>

                                @endif

                            </td>


                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            @else

            <div class="empty">

                <div class="empty-icon">
                    📊
                </div>

                <h3>
                    Belum Ada Riwayat
                </h3>

                <p>
                    Belum ada antrean yang selesai atau dilewati oleh Anda.
                </p>

            </div>

            @endif


        </div>


    </main>


</body>

</html>