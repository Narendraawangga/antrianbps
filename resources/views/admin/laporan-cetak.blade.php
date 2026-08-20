<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Antrean BPS Kolaka Utara
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            color: #222;
            background: white;
        }

        .print-container {
            max-width: 1000px;
            margin: auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            font-size: 13px;
        }

        .periode {
            margin-bottom: 20px;
            font-size: 13px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 25px;
        }

        .stat {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        .stat strong {
            display: block;
            font-size: 20px;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #bbb;
            padding: 8px;
            font-size: 11px;
            text-align: left;
        }

        th {
            background: #f1f1f1;
        }

        .footer {
            margin-top: 35px;
            display: flex;
            justify-content: flex-end;
        }

        .signature {
            width: 220px;
            text-align: center;
            font-size: 12px;
        }

        .signature-space {
            height: 70px;
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
            }

            body {
                font-size: 11px;
                background: #ffffff;
            }

            .print-container {
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 0;
            }

            .header {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .logo-bps {
                width: 65px !important;
                height: 65px !important;
                max-width: 65px !important;
                max-height: 65px !important;
            }

            .stats {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            table {
                width: 100%;
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .footer {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
    </style>

</head>


<body>

    <div class="print-container">


        <!-- HEADER -->

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


        <!-- PERIODE -->

        <div class="periode">

            <strong>
                Periode:
            </strong>

            {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}

            s/d

            {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}

        </div>


        <!-- STATISTIK -->

        <div class="stats">

            <div class="stat">
                Total
                <strong>{{ $total }}</strong>
            </div>

            <div class="stat">
                Menunggu
                <strong>{{ $waiting }}</strong>
            </div>

            <div class="stat">
                Dilayani
                <strong>{{ $serving }}</strong>
            </div>

            <div class="stat">
                Selesai
                <strong>{{ $completed }}</strong>
            </div>

            <div class="stat">
                Dilewati
                <strong>{{ $skipped }}</strong>
            </div>

        </div>


        <!-- DETAIL -->

        <h3>
            Daftar Antrean
        </h3>

        <table>

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
                        Tanggal
                    </th>

                    <th>
                        Waktu
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Petugas
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse ($antrians as $index => $antrian)

                <tr>

                    <td>
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $antrian->queue_number }}
                    </td>

                    <td>
                        {{ $antrian->service?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $antrian->created_at?->format('d/m/Y') }}
                    </td>

                    <td>
                        {{ $antrian->created_at?->format('H:i') }}
                    </td>

                    <td>
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
                        style="text-align:center">

                        Tidak ada data antrean.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>


        <!-- FOOTER -->

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