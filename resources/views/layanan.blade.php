<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        Ambil Antrean - BPS Kolaka Utara
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/layanan.css') }}"
    >

    <script
        src="{{ asset('js/layanan.js') }}"
        defer
    ></script>
</head>


<body
    data-submit-url="{{ route('ambil.antrian') }}"
>

    {{-- =====================================================
        BACKGROUND DECORATION
    ====================================================== --}}

    <div class="background-decoration">

        <div class="circle circle-one"></div>

        <div class="circle circle-two"></div>

        <div class="circle circle-three"></div>

    </div>


    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <header class="page-header">

        <a
            href="{{ url('/') }}"
            class="back-button"
        >

            <span class="back-icon">
                ←
            </span>

            <span>
                Kembali
            </span>

        </a>


        <div class="brand">

            <div
                class="bps-logo"
                aria-hidden="true"
            >

                <span></span>
                <span></span>
                <span></span>

            </div>


            <div class="brand-text">

                <strong>
                    BADAN PUSAT STATISTIK
                </strong>

                <span>
                    Kabupaten Kolaka Utara
                </span>

            </div>

        </div>

    </header>


    {{-- =====================================================
        MAIN
    ====================================================== --}}

    <main class="main-container">


        {{-- =================================================
            JUDUL
        ================================================== --}}

        <section class="page-intro">

            <span class="intro-badge">
                PELAYANAN STATISTIK TERPADU
            </span>


            <h1>
                Ambil Nomor Antrean
            </h1>


            <p>
                Masukkan nama, pilih layanan,
                kemudian ambil foto untuk mendapatkan
                nomor antrean.
            </p>

        </section>



        {{-- =================================================
            CARD FORM
        ================================================== --}}

        <section class="queue-card">


            {{-- =============================================
                BAGIAN KIRI
            ============================================== --}}

            <div class="form-section">


                {{-- =========================================
                    STEP 1
                ========================================== --}}

                <div class="section-heading">

                    <span class="step-number">
                        1
                    </span>


                    <div>

                        <h2>
                            Masukkan Nama
                        </h2>

                        <p>
                            Masukkan nama pengunjung
                            yang akan menerima pelayanan.
                        </p>

                    </div>

                </div>


                <div class="form-group">

                    <label
                        for="visitorName"
                    >
                        Nama Pengunjung
                    </label>


                    <div class="input-wrapper">

                        <span class="input-icon">
                            👤
                        </span>


                        <input
                            type="text"
                            id="visitorName"
                            class="visitor-input"
                            placeholder="Contoh: Narendra Awangga"
                            maxlength="100"
                            autocomplete="name"
                        >

                    </div>


                    <small
                        id="nameHint"
                        class="form-hint"
                    >
                        Minimal 2 karakter.
                    </small>

                </div>



                {{-- =========================================
                    STEP 2
                ========================================== --}}

                <div class="section-heading service-heading">

                    <span class="step-number">
                        2
                    </span>


                    <div>

                        <h2>
                            Pilih Layanan
                        </h2>

                        <p>
                            Pilih jenis pelayanan
                            yang Anda butuhkan.
                        </p>

                    </div>

                </div>



                {{-- =========================================
                    SERVICES
                ========================================== --}}

                <div class="services">

                    @forelse ($services as $service)

                        <button
                            type="button"
                            class="service-button"

                            data-service-id="{{ $service->id }}"

                            data-service-name="{{ $service->name }}"

                            data-service-code="{{ $service->code }}"
                        >

                            <span class="service-code">

                                {{ $service->code }}

                            </span>


                            <span class="service-info">

                                <strong>

                                    {{ $service->name }}

                                </strong>


                                @if ($service->description)

                                    <small>

                                        {{ $service->description }}

                                    </small>

                                @else

                                    <small>

                                        Pilih layanan ini

                                    </small>

                                @endif

                            </span>


                            <span class="service-check">
                                ✓
                            </span>

                        </button>

                    @empty

                        <div class="empty-service">

                            Tidak ada layanan
                            yang sedang aktif.

                        </div>

                    @endforelse

                </div>


            </div>



            {{-- =================================================
                BAGIAN KANAN - CAMERA
            ================================================== --}}

            <div class="camera-section">


                {{-- =============================================
                    STEP 3
                ============================================== --}}

                <div class="section-heading">

                    <span class="step-number">
                        3
                    </span>


                    <div>

                        <h2>
                            Ambil Foto
                        </h2>

                        <p>
                            Pastikan wajah terlihat
                            jelas pada kamera.
                        </p>

                    </div>

                </div>



                {{-- =============================================
                    CAMERA
                ============================================== --}}

                <div class="camera-box">


                    <video
                        id="video"
                        autoplay
                        playsinline
                        muted
                    >
                    </video>


                    <img
                        id="photoPreview"
                        alt="Foto pengunjung"
                    >


                    {{-- PLACEHOLDER --}}

                    <div
                        id="cameraPlaceholder"
                        class="camera-placeholder"
                    >

                        <div class="camera-placeholder-icon">

                            📷

                        </div>


                        <strong>

                            Mengaktifkan kamera...

                        </strong>


                        <span>

                            Silakan izinkan akses kamera
                            pada browser.

                        </span>

                    </div>



                    {{-- CAMERA STATUS --}}

                    <div
                        id="cameraStatus"
                        class="camera-status"
                    >

                        <span class="status-dot"></span>

                        Kamera Aktif

                    </div>


                </div>



                {{-- =============================================
                    CAMERA ERROR
                ============================================== --}}

                <div
                    id="cameraError"
                    class="camera-error"
                >

                    <strong>
                        Kamera tidak dapat digunakan
                    </strong>

                    <span>
                        Pastikan browser memiliki izin
                        untuk menggunakan kamera.
                    </span>

                </div>



                {{-- =============================================
                    INFORMASI PILIHAN
                ============================================== --}}

                <div class="selected-summary">


                    <div class="summary-item">

                        <span>
                            Nama
                        </span>


                        <strong
                            id="summaryName"
                        >
                            Belum diisi
                        </strong>

                    </div>


                    <div class="summary-divider"></div>


                    <div class="summary-item">

                        <span>
                            Layanan
                        </span>


                        <strong
                            id="summaryService"
                        >
                            Belum dipilih
                        </strong>

                    </div>


                </div>



                {{-- =============================================
                    BUTTON AMBIL ANTREAN
                ============================================== --}}

                <button
                    type="button"
                    id="takeQueueButton"
                    class="take-queue-button"
                    disabled
                >

                    <span class="button-icon">
                        📷
                    </span>


                    <span>

                        Ambil Foto & Nomor Antrean

                    </span>

                </button>



                {{-- =============================================
                    LOADING
                ============================================== --}}

                <div
                    id="loading"
                    class="loading"
                >

                    <span class="loading-spinner"></span>


                    <span>

                        Sedang membuat nomor antrean...

                    </span>

                </div>


                {{-- =============================================
                    INFO
                ============================================== --}}

                <div class="privacy-note">

                    <span>
                        🔒
                    </span>


                    <p>

                        Foto digunakan untuk membantu
                        petugas mengenali pengunjung
                        selama proses pelayanan.

                    </p>

                </div>


            </div>


        </section>


    </main>



    {{-- =====================================================
        CANVAS FOTO
    ====================================================== --}}

    <canvas
        id="canvas"
        hidden
    >
    </canvas>



    {{-- =====================================================
        MODAL HASIL ANTREAN
    ====================================================== --}}

    <div
        id="resultModal"
        class="result-modal"
    >

        <div class="result-card">


            {{-- =============================================
                SUCCESS
            ============================================== --}}

            <div class="success-icon">

                ✓

            </div>


            <span class="result-label">

                ANTREAN BERHASIL DIBUAT

            </span>



            {{-- =============================================
                QUEUE NUMBER
            ============================================== --}}

            <div
                id="resultQueueNumber"
                class="result-queue-number"
            >

                A-001

            </div>



            {{-- =============================================
                NAMA
            ============================================== --}}

            <div
                id="resultVisitorName"
                class="result-visitor-name"
            >

                Nama Pengunjung

            </div>



            {{-- =============================================
                SERVICE
            ============================================== --}}

            <div
                id="resultService"
                class="result-service"
            >

                Pelayanan

            </div>



            {{-- =============================================
                FOTO
            ============================================== --}}

            <div class="result-photo-wrapper">

                <img
                    id="resultPhoto"
                    class="result-photo"
                    alt="Foto pengunjung"
                >

            </div>



            {{-- =============================================
                PESAN
            ============================================== --}}

            <div class="display-message">


                <span class="display-icon">

                    📺

                </span>


                <div>

                    <strong>

                        Silakan menunggu

                    </strong>


                    <p>

                        Perhatikan layar display.
                        Nama dan nomor antrean Anda
                        akan ditampilkan ketika dipanggil.

                    </p>

                </div>


            </div>



            {{-- =============================================
                BUTTON SELESAI
            ============================================== --}}

            <button
                type="button"
                id="finishButton"
                class="finish-button"
            >

                Selesai

            </button>


        </div>

    </div>



    {{-- =====================================================
        NOTIFICATION
    ====================================================== --}}

    <div
        id="notification"
        class="notification"
    >

        <span
            id="notificationIcon"
            class="notification-icon"
        >
            !
        </span>


        <span
            id="notificationText"
        >
            Pesan
        </span>

    </div>


</body>

</html>