<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Display Antrean - BPS Kolaka Utara
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite([
        'resources/css/display/index.css',
        'resources/js/display/index.js',
    ])

</head>

<body>

<div class="display-page">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <header class="display-header">

        <div class="display-title">

           <div class="display-logo">

    <img
        src="{{ asset('images/hh.png') }}"
        alt="Logo BPS"
        class="display-logo-image"
    >

</div>

            <div>

                <h1>
                    BPS Kabupaten Kolaka Utara
                </h1>

                <p>
                    Sistem Antrean Pelayanan Statistik
                </p>

            </div>

        </div>


        <div class="display-clock">

            <div
                class="display-time"
                id="displayTime"
            >
                --:--:--
            </div>

            <div
                class="display-date"
                id="displayDate"
            >
                -
            </div>

        </div>

    </header>


    {{-- =========================================================
        CONTENT
    ========================================================== --}}

    <main class="monitor-layout">


        {{-- =====================================================
            BAGIAN KIRI - ANTREAN
        ====================================================== --}}

        <section class="monitor-left">


            {{-- JUDUL --}}

            <div class="queue-panel-title">

                <span class="queue-panel-label">
                    ANTREAN DIPANGGIL
                </span>

                <p>
                    Silakan menuju layanan
                </p>

            </div>


            {{-- =================================================
                ANTREAN YANG SEDANG DIPANGGIL
            ================================================== --}}

            <div
                id="queueContainer"
                class="current-queue-container"
                data-url="{{ route('display.data') }}"
            >

                @if ($currentQueues->isNotEmpty())

                    @foreach ($currentQueues as $queue)

                        <div
                            class="queue-card queue-card-main"
                            data-queue-id="{{ $queue->id }}"
                        >

                            <div class="queue-number">
                                {{ $queue->queue_number }}
                            </div>


                            <div class="queue-service">
                                {{ $queue->service->name }}
                            </div>


                            <div
                                class="queue-status
                                status-{{ $queue->status }}"
                            >
                                {{ $queue->status_label }}
                            </div>

                        </div>

                    @endforeach

                @else

                    <div class="queue-empty">

                        {{-- Ikon digambar via CSS (::before mask) di index.css,
                             supaya tetap konsisten walau elemen ini nanti
                             dirender ulang oleh JavaScript --}}
                        <div class="queue-empty-icon"></div>

                        <h2>
                            Belum Ada Antrean Dipanggil
                        </h2>

                        <p>
                            Nomor antrean akan tampil di sini ketika petugas melakukan pemanggilan.
                        </p>

                    </div>

                @endif

            </div>


            {{-- =================================================
                ANTREAN BERIKUTNYA
            ================================================== --}}

            <div class="next-queue-section">

                <div class="next-queue-header">

                    <span>
                        Antrean Berikutnya
                    </span>

                </div>


                <div
                    class="next-queue-list"
                    id="nextQueueList"
                >

                    <div class="next-queue-empty">
                        Menunggu antrean berikutnya
                    </div>

                </div>

            </div>

        </section>



        {{-- =====================================================
            BAGIAN KANAN - VIDEO
        ====================================================== --}}

        <section class="monitor-right">

            <div class="video-panel">


                {{-- HEADER VIDEO --}}

                <div class="video-panel-header">

                    <div>

                        <h2>
                            Informasi BPS Kolaka Utara
                        </h2>

                        <p>
                            Informasi statistik dan kegiatan BPS
                        </p>

                    </div>


                    <div class="video-live-badge">

                        <span class="video-live-dot"></span>

                        Video Informasi

                    </div>

                </div>


                {{-- VIDEO YOUTUBE --}}

                <div class="video-wrapper">
                <div
                id="bpsYoutubePlayer"
                data-video-id="jZd5KMYl-kM"
                 ></div>

                </div>


                {{-- FOOTER VIDEO --}}

                <div class="video-footer">

                    <div class="video-footer-item">

                        <strong>
                            BPS Kabupaten Kolaka Utara
                        </strong>

                        <span>
                            Data Mencerdaskan Bangsa
                        </span>

                    </div>

                </div>

            </div>

        </section>


    </main>

</div>

</body>

</html>