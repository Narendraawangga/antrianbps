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

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >


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


            {{-- LOGO --}}

            <div class="display-logo">

                <img
                    src="{{ asset('images/hh.png') }}"
                    alt="Logo BPS"
                    class="display-logo-image"
                >

            </div>



            {{-- JUDUL --}}

            <div>

                <h1>
                    BPS Kabupaten Kolaka Utara
                </h1>

                <p>
                    Sistem Antrean Pelayanan Statistik
                </p>

            </div>

        </div>



        {{-- JAM --}}

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
            BAGIAN KIRI
            3 PELAYANAN
        ====================================================== --}}

        <section class="monitor-left">


            {{-- JUDUL --}}

            <div class="queue-panel-title">

                <span class="queue-panel-label">
                    ANTREAN PELAYANAN
                </span>

                <p>
                    Perhatikan nomor antrean dan pelayanan Anda
                </p>

            </div>



            {{-- =================================================
                3 KARTU PELAYANAN
            ================================================== --}}

            <div
                class="service-display-grid"
                id="serviceDisplayContainer"
                data-url="{{ route('display.data') }}"
            >


                @forelse ($displayServices as $item)


                    @php

                        $service =
                            $item['service'];

                        $currentQueue =
                            $item['current_queue'];

                        $nextQueues =
                            $item['next_queues'];

                    @endphp



                    {{-- =================================================
                        KARTU PELAYANAN
                    ================================================== --}}

                    <article
                        class="service-counter-card"
                        data-service-id="{{ $service->id }}"
                    >


                        {{-- =============================================
                            HEADER PELAYANAN
                        ============================================== --}}

                        <div class="service-counter-header">


                            <div class="service-counter-title">


                                <div class="service-counter-label">
                                    PELAYANAN
                                </div>


                                <h2
                                    class="service-name"
                                    data-service-name
                                >
                                    {{ $service->name }}
                                </h2>

                            </div>



                            {{-- KODE LAYANAN --}}

                            <div class="service-code">

                                {{ $service->code }}

                            </div>

                        </div>



                        {{-- =============================================
                            NOMOR ANTREAN AKTIF
                        ============================================== --}}

                        <div class="service-current-queue">


                            <div class="service-current-label">

                                NOMOR ANTREAN

                            </div>



                            <div
                                class="service-current-number"
                                data-current-number
                            >

                                @if ($currentQueue)

                                    {{ $currentQueue->queue_number }}

                                @else

                                    -

                                @endif

                            </div>



                            {{-- STATUS --}}

                            <div
                                class="
                                    service-current-status

                                    @if ($currentQueue)
                                        status-{{ $currentQueue->status }}
                                    @else
                                        status-empty
                                    @endif
                                "
                                data-current-status
                            >

                                @if ($currentQueue)

                                    {{ $currentQueue->status_label }}

                                @else

                                    Menunggu Panggilan

                                @endif

                            </div>

                        </div>



                        {{-- =============================================
                            ANTREAN BERIKUTNYA
                        ============================================== --}}

                        <div class="service-next-section">


                            <div class="service-next-header">

                                Antrean Berikutnya

                            </div>



                            <div
                                class="service-next-list"
                                data-next-list
                            >


                                @forelse ($nextQueues as $queue)


                                    <div class="service-next-item">

                                        <span class="service-next-number">

                                            {{ $queue->queue_number }}

                                        </span>

                                    </div>


                                @empty


                                    <div class="service-next-empty">

                                        Belum ada antrean menunggu

                                    </div>


                                @endforelse


                            </div>

                        </div>

                    </article>


                @empty


                    {{-- =================================================
                        JIKA TIDAK ADA PELAYANAN
                    ================================================== --}}

                    <div class="queue-empty">


                        <div class="queue-empty-icon"></div>


                        <h2>

                            Belum Ada Pelayanan Aktif

                        </h2>


                        <p>

                            Pelayanan aktif belum tersedia.

                        </p>

                    </div>


                @endforelse


            </div>

        </section>



        {{-- =====================================================
            BAGIAN KANAN
            VIDEO BPS
        ====================================================== --}}

        <section class="monitor-right">


            <div class="video-panel">


                {{-- =============================================
                    HEADER VIDEO
                ============================================== --}}

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



                {{-- =============================================
                    YOUTUBE
                ============================================== --}}

                <div class="video-wrapper">

                    <div
                        id="bpsYoutubePlayer"
                        data-video-id="jZd5KMYl-kM"
                    ></div>

                </div>



                {{-- =============================================
                    FOOTER
                ============================================== --}}

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