<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Laporan Pelayanan Antrean</title>


    <style>
        /* =====================================================
           RESET
        ===================================================== */

        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            color: #1f2937;

            background: #eef2f7;

            font-size: 11px;
        }


        /* =====================================================
           CONTAINER
        ===================================================== */

        .print-container {

            width: 100%;

            max-width: 1120px;

            margin: 25px auto;

            padding: 28px;

            background: #ffffff;

            box-shadow:
                0 4px 20px rgba(15, 23, 42, .08);

        }


        /* =====================================================
           HEADER
        ===================================================== */

        .header {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 18px;

            padding-bottom: 14px;

            margin-bottom: 18px;

            border-bottom: 2px solid #244da8;

        }


        .logo-bps {

            width: 68px;

            height: 68px;

            max-width: 68px;

            max-height: 68px;

            object-fit: contain;

            flex-shrink: 0;

        }


        .header-text {

            text-align: center;

        }


        .header h1 {

            margin: 0;

            font-size: 20px;

            font-weight: 700;

            color: #111827;

        }


        .header h2 {

            margin: 4px 0;

            font-size: 16px;

            font-weight: 700;

            color: #111827;

        }


        .header p {

            margin: 5px 0 0;

            font-size: 11px;

            font-weight: 600;

            color: #244da8;

        }


        /* =====================================================
           PERIODE
        ===================================================== */

        .periode {

            margin-bottom: 18px;

            font-size: 11px;

            color: #475569;

        }


        .periode strong {

            color: #1f2937;

        }


        /* =====================================================
           STATISTIK
        ===================================================== */

        .stats {

            display: grid;

            grid-template-columns:
                repeat(5, 1fr);

            gap: 8px;

            margin-bottom: 22px;

        }


        .stat {

            padding: 9px 8px;

            text-align: center;

            border: 1px solid #dbe2ea;

            border-radius: 6px;

            background: #ffffff;

            color: #64748b;

            font-size: 9px;

        }


        .stat strong {

            display: block;

            margin-top: 5px;

            font-size: 17px;

            line-height: 1;

            color: #244da8;

        }


        /* =====================================================
           JUDUL TABEL
        ===================================================== */

        .section-title {

            margin: 0 0 8px;

            font-size: 13px;

            font-weight: 700;

            color: #244da8;

        }


        /* =====================================================
           TABLE
        ===================================================== */

        table {

            width: 100%;

            max-width: 100%;

            border-collapse: collapse;

            table-layout: fixed;

            margin-top: 0;

        }


        th,
        td {

            border: 1px solid #d8dee7;

            padding: 7px 6px;

            font-size: 9px;

            vertical-align: middle;

            word-wrap: break-word;

        }


        th {

            background: #f1f5f9;

            color: #334155;

            font-size: 8px;

            font-weight: 700;

            text-align: center;

            text-transform: uppercase;

        }


        td {

            color: #475569;

        }


        tbody tr:nth-child(even) {

            background: #fafcff;

        }


        .center {

            text-align: center;

        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {

            margin-top: 28px;

            display: flex;

            justify-content: flex-end;

        }


        .signature {

            width: 220px;

            text-align: center;

            font-size: 10px;

            color: #475569;

        }


        .signature-space {

            height: 55px;

        }


        /* =====================================================
           BUTTON CETAK
        ===================================================== */

        .print-actions {

            position: fixed;

            top: 15px;

            right: 15px;

            display: flex;

            gap: 7px;

            z-index: 999;

        }


        .print-button,
        .close-button {

            height: 36px;

            padding: 0 13px;

            border-radius: 7px;

            font-size: 11px;

            font-weight: 600;

            cursor: pointer;

        }


        .print-button {

            border: none;

            background: #244da8;

            color: #ffffff;

        }


        .print-button:hover {

            background: #1d4294;

        }


        .close-button {

            border: 1px solid #dbe2ea;

            background: #ffffff;

            color: #475569;

        }


        .close-button:hover {

            background: #f8fafc;

        }


        /* =====================================================
           PRINT
        ===================================================== */

        @page {

            size: A4 landscape;

            margin: 10mm;

        }


        @media print {

            * {

                -webkit-print-color-adjust: exact;

                print-color-adjust: exact;

            }


            html,
            body {

                width: 100%;

                margin: 0;

                padding: 0;

                background: #ffffff;

            }


            .print-container {

                width: 100%;

                max-width: none;

                margin: 0;

                padding: 0;

                box-shadow: none;

            }


            .print-actions {

                display: none !important;

            }


            .header {

                page-break-inside: avoid;

                break-inside: avoid;

            }


            .stats {

                page-break-inside: avoid;

                break-inside: avoid;

            }


            table {

                width: 100%;

                max-width: 100%;

            }


            thead {

                display: table-header-group;

            }


            tr {

                page-break-inside: avoid;

                break-inside: avoid;

            }


            .footer {

                page-break-inside: avoid;

                break-inside: avoid;

            }

        }


        /* =====================================================
           SCREEN RESPONSIVE
        ===================================================== */

        @media screen and (max-width: 850px) {

            .print-container {

                width: calc(100% - 24px);

                margin: 12px;

                padding: 20px;

            }


            .header {

                flex-direction: column;

            }


            .stats {

                grid-template-columns:
                    repeat(2, 1fr);

            }

        }


        @media screen and (max-width: 500px) {

            .print-container {

                padding: 14px;

            }


            .stats {

                grid-template-columns: 1fr;

            }


            th,
            td {

                font-size: 8px;

                padding: 5px 4px;

            }

        }
    </style>

</head>


<body>


    <!-- =====================================================
         BUTTON
    ===================================================== -->

    <div class="print-actions">

        <button
            type="button"
            class="print-button"
            onclick="window.print()">

            🖨 Cetak / Simpan PDF

        </button>


        <button
            type="button"
            class="close-button"
            onclick="window.close()">

            Tutup

        </button>

    </div>


    <div class="print-container">


        <!-- =================================================
             HEADER
        ================================================== -->

        <div class="header">


            <img
                src="{{ asset('images/hh.png') }}"
                alt="Logo BPS"
                class="logo-bps">


            <div class="header-text">

                <h1>
                    BADAN PUSAT STATISTIK
                </h1>

                <h2>
                    KABUPATEN KOLAKA UTARA
                </h2>

                <p>
                    LAPORAN PELAYANAN ANTREAN
                </p>

            </div>


        </div>


        <!-- =================================================
             PERIODE
        ================================================== -->

        <div class="periode">

            <strong>
                Periode:
            </strong>

            {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}

            s/d

            {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}

        </div>


        <!-- =================================================
             STATISTIK
        ================================================== -->

        <div class="stats">


            <div class="stat">

                Total

                <strong>
                    {{ $total }}
                </strong>

            </div>


            <div class="stat">

                Menunggu

                <strong>
                    {{ $waiting }}
                </strong>

            </div>


            <div class="stat">

                Dilayani

                <strong>
                    {{ $serving }}
                </strong>

            </div>


            <div class="stat">

                Selesai

                <strong>
                    {{ $completed }}
                </strong>

            </div>


            <div class="stat">

                Dilewati

                <strong>
                    {{ $skipped }}
                </strong>

            </div>


        </div>


        <!-- =================================================
             DETAIL
        ================================================== -->

        <h3 class="section-title">

            Daftar Antrean

        </h3>


        <table>


            <thead>

                <tr>

                    <th style="width: 5%;">
                        No
                    </th>

                    <th style="width: 14%;">
                        Nomor Antrean
                    </th>

                    <th style="width: 23%;">
                        Layanan
                    </th>

                    <th style="width: 12%;">
                        Tanggal
                    </th>

                    <th style="width: 10%;">
                        Waktu
                    </th>

                    <th style="width: 15%;">
                        Status
                    </th>

                    <th style="width: 21%;">
                        Petugas
                    </th>

                </tr>

            </thead>


            <tbody>


                @forelse ($antrians as $index => $antrian)


                <tr>


                    <td class="center">

                        {{ $index + 1 }}

                    </td>


                    <td class="center">

                        <strong>

                            {{ $antrian->queue_number }}

                        </strong>

                    </td>


                    <td>

                        {{ $antrian->service?->name ?? '-' }}

                    </td>


                    <td class="center">

                        {{ $antrian->created_at?->format('d/m/Y') }}

                    </td>


                    <td class="center">

                        {{ $antrian->created_at?->format('H:i') }}

                    </td>


                    <td class="center">

                        {{ $antrian->status_label }}

                    </td>


                    <td>

                        {{ $antrian->servedBy?->name ?? '-' }}

                    </td>


                </tr>


                @empty


                <tr>

                    <td
                        colspan="7"
                        class="center">

                        Tidak ada data antrean.

                    </td>

                </tr>


                @endforelse


            </tbody>


        </table>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="footer">


            <div class="signature">

                Kolaka Utara,

                {{ now()->format('d/m/Y') }}


                <br>


                Petugas/Admin


                <div class="signature-space"></div>


                ______________________

            </div>


        </div>


    </div>


    <script>
        window.onload = function() {

            window.print();

        };
    </script>


</body>

</html>