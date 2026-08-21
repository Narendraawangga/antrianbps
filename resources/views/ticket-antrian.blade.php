<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Tiket {{ $queue->queue_number }} - BPS Kolaka Utara
    </title>

    {{-- CSS TIKET --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/ticket-antrian.css') }}"
    >


    {{-- LIBRARY QR CODE --}}
    <script
        src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"
        defer
    ></script>


    {{-- JAVASCRIPT TIKET --}}
    <script
        src="{{ asset('js/ticket-antrian.js') }}"
        defer
    ></script>
</head>


@php

    /*
    |--------------------------------------------------------------------------
    | TIMEZONE
    |--------------------------------------------------------------------------
    */

    $timezone =
        config(
            'antrian.timezone',
            'Asia/Makassar'
        );


    /*
    |--------------------------------------------------------------------------
    | WAKTU ANTREAN
    |--------------------------------------------------------------------------
    */

    $queueTime =
        $queue->created_at
            ->copy()
            ->timezone(
                $timezone
            );


    /*
    |--------------------------------------------------------------------------
    | URL KEMBALI
    |--------------------------------------------------------------------------
    |
    | Jika PC kiosk:
    |
    | /layanan?kiosk=1
    |
    | Jika HP:
    |
    | /
    |
    */

    $returnUrl =
        $kiosk
            ? url(
                '/layanan?kiosk=1'
            )
            : url('/');

@endphp


<body

    data-kiosk="{{ $kiosk ? '1' : '0' }}"

    data-status-url="{{ $statusUrl }}"

    data-return-url="{{ $returnUrl }}"

>

    <main class="ticket-page">


        {{-- =========================================================
            HEADER HALAMAN
        ========================================================== --}}

        <section
            class="screen-heading screen-only"
        >

            <div>

                <span class="eyebrow">
                    TIKET DIGITAL
                </span>


                <h1>
                    Nomor antrean berhasil dibuat
                </h1>


                <p>
                    Simpan tiket ini atau scan QR untuk
                    memantau status antrean melalui HP.
                </p>

            </div>


            {{-- MODE KIOSK --}}

            @if ($kiosk)

                <span class="kiosk-badge">
                    PC Kiosk BPS
                </span>

            @endif

        </section>



        {{-- =========================================================
            LAYOUT UTAMA
        ========================================================== --}}

        <div class="ticket-layout">


            {{-- =====================================================
                TIKET
            ====================================================== --}}

            <article
                id="printTicket"
                class="thermal-ticket"
            >


                {{-- LOGO / BRAND --}}

                <div class="ticket-brand">


                    <div
                        class="bps-mark"
                        aria-hidden="true"
                    >

                        <span></span>

                        <span></span>

                        <span></span>

                    </div>


                    <div>

                        <strong>
                            BADAN PUSAT STATISTIK
                        </strong>


                        <span>
                            KABUPATEN KOLAKA UTARA
                        </span>

                    </div>

                </div>



                {{-- GARIS WARNA BPS --}}

                <div class="ticket-line"></div>



                {{-- LABEL --}}

                <div class="ticket-label">
                    NOMOR ANTREAN
                </div>



                {{-- NOMOR ANTREAN --}}

                <div class="ticket-queue-number">

                    {{ $queue->queue_number }}

                </div>



                {{-- LAYANAN --}}

                <div class="ticket-service">

                    {{
                        $queue->service?->name
                        ?? '-'
                    }}

                </div>



                {{-- =================================================
                    INFORMASI WAKTU
                ================================================== --}}

                <div class="ticket-meta">


                    <div>

                        <span>
                            Tanggal
                        </span>


                        <strong>

                            {{
                                $queueTime
                                    ->format(
                                        'd/m/Y'
                                    )
                            }}

                        </strong>

                    </div>



                    <div>

                        <span>
                            Waktu
                        </span>


                        <strong>

                            {{
                                $queueTime
                                    ->format(
                                        'H:i'
                                    )
                            }}

                            WITA

                        </strong>

                    </div>

                </div>



                {{-- GARIS PUTUS --}}

                <div class="ticket-dashed"></div>



                {{-- =================================================
                    QR CODE
                ================================================== --}}

                <div class="qr-wrapper">


                    <div
                        id="queueQr"
                        class="qr-code"
                        aria-label="QR status antrean"
                    >
                    </div>


                    <p>

                        Scan QR untuk memantau
                        status antrean melalui HP.

                    </p>

                </div>



                {{-- GARIS PUTUS --}}

                <div class="ticket-dashed"></div>



                {{-- =================================================
                    PESAN
                ================================================== --}}

                <div class="ticket-message">


                    <strong>

                        Mohon menunggu panggilan petugas.

                    </strong>


                    <span>

                        Pastikan nomor antrean Anda
                        terdengar atau terlihat
                        pada layar display.

                    </span>

                </div>



                {{-- =================================================
                    FOOTER TIKET
                ================================================== --}}

                <div class="ticket-footer">

                    Pelayanan Statistik Terpadu

                    <br>

                    BPS Kabupaten Kolaka Utara

                </div>


            </article>



            {{-- =====================================================
                PANEL AKSI
            ====================================================== --}}

            <aside
                class="action-panel screen-only"
            >


                {{-- ICON SUCCESS --}}

                <div class="action-icon">

                    ✓

                </div>



                <h2>

                    Antrean Anda siap

                </h2>



                <p>

                    Nomor

                    <strong>

                        {{ $queue->queue_number }}

                    </strong>

                    sudah masuk ke sistem antrean.

                </p>



                {{-- =================================================
                    JIKA MODE KIOSK
                ================================================== --}}

                @if ($kiosk)


                    <div class="kiosk-note">


                        <span>
                            🖨️
                        </span>


                        <div>


                            <strong>

                                Silakan cetak tiket

                            </strong>


                            <p>

                                Setelah tiket dicetak,
                                halaman akan kembali otomatis
                                agar PC siap digunakan
                                pengunjung berikutnya.

                            </p>


                        </div>


                    </div>


                @else


                    {{-- =================================================
                        JIKA HP
                    ================================================== --}}

                    <div class="phone-note">


                        <span>
                            📱
                        </span>


                        <div>


                            <strong>

                                Menggunakan HP?

                            </strong>


                            <p>

                                Anda tidak wajib mencetak tiket.

                                Tekan tombol
                                Pantau Antrean
                                untuk melihat status antrean.

                            </p>


                        </div>


                    </div>


                @endif



                {{-- =================================================
                    BUTTON
                ================================================== --}}

                <div class="action-buttons">


                    {{-- CETAK --}}

                    <button

                        type="button"

                        id="printButton"

                        class="button button-primary"

                    >

                        🖨 Cetak Tiket

                    </button>



                    {{-- =================================================
                        TOMBOL STATUS HANYA UNTUK HP
                    ================================================== --}}

                    @if (!$kiosk)


                        <a

                            href="{{ $statusUrl }}"

                            class="button button-secondary"

                        >

                            📱 Pantau Antrean

                        </a>


                    @endif



                    {{-- =================================================
                        SELESAI
                    ================================================== --}}

                    <button

                        type="button"

                        id="doneButton"

                        class="button button-ghost"

                    >

                        @if ($kiosk)

                            Selesai

                        @else

                            Kembali ke Beranda

                        @endif

                    </button>


                </div>



                {{-- =================================================
                    AUTO RESET KIOSK
                ================================================== --}}

                @if ($kiosk)


                    <div

                        id="resetNotice"

                        class="reset-notice"

                    >

                        PC akan kembali otomatis
                        jika tidak digunakan.

                    </div>


                @endif


            </aside>


        </div>


    </main>


</body>

</html>