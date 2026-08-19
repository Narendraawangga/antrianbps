<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pilih Layanan - BPS Kolaka Utara</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;

            background: linear-gradient(120deg,
                    #f5f9ff,
                    #eefbfa);

            color: #111;
        }

        /* =========================
           BACK
        ========================= */

        .back {
            position: absolute;
            top: 22px;
            left: 20px;

            display: flex;
            align-items: center;
            gap: 10px;

            color: #111;
            text-decoration: none;

            font-size: 18px;

            z-index: 10;
        }

        .back-arrow {
            font-size: 35px;
            line-height: 1;
        }

        .back:hover {
            color: #2349ad;
        }

        /* =========================
           CONTAINER
        ========================= */

        .container {
            width: min(1075px, 92%);

            margin: 0 auto;

            padding-top: 210px;
            padding-bottom: 50px;
        }

        /* =========================
           CAMERA CONTAINER
        ========================= */

        .camera-container {
            width: 100%;

            border: 1px dashed #aaa;

            border-radius: 10px;

            padding: 25px 100px 10px;

            background: rgba(255, 255, 255, .15);
        }

        /* =========================
           CAMERA
        ========================= */

        .camera-screen {
            width: 100%;
            height: 240px;

            background: #000;

            position: relative;

            overflow: hidden;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        #video,
        #photoPreview {

            width: 100%;
            height: 100%;

            object-fit: cover;
        }

        #video {
            display: none;
        }

        #photoPreview {
            display: none;
        }

        .camera-placeholder {

            color: white;

            text-align: center;

            font-size: 17px;

            line-height: 1.5;
        }

        .camera-status {

            position: absolute;

            top: 12px;
            right: 12px;

            padding: 7px 12px;

            border-radius: 20px;

            background: rgba(0, 0, 0, .6);

            color: white;

            font-size: 13px;

            display: none;
        }

        .camera-status.active {

            display: block;

            background: #159447;
        }

        /* =========================
           ERROR
        ========================= */

        .camera-error {

            display: none;

            margin-top: 12px;

            padding: 10px;

            text-align: center;

            background: #ffe8e8;

            color: #b00020;

            border-radius: 7px;

            font-size: 14px;
        }

        .camera-error.show {
            display: block;
        }

        /* =========================
           SERVICE TITLE
        ========================= */

        .service-title {

            text-align: center;

            font-size: 18px;

            margin-top: 27px;

            margin-bottom: 10px;
        }

        /* =========================
           SERVICES
        ========================= */

        .services {

            display: flex;

            justify-content: center;

            flex-wrap: wrap;

            gap: 2px;
        }

        .service-btn {

            background: #eee;

            border: 2px solid #777;

            border-radius: 4px;

            padding: 11px 14px;

            font-size: 17px;

            cursor: pointer;

            transition: .2s;
        }

        .service-btn:hover {

            background: #ddd;
        }

        .service-btn.active {

            background: #2349ad;

            color: white;

            border-color: #2349ad;
        }

        /* =========================
           PHOTO BUTTON
        ========================= */

        .photo-btn {

            width: 90%;

            height: 44px;

            margin: 21px auto 0;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 10px;

            border: none;

            border-radius: 15px;

            background: #cfcfcf;

            color: #666;

            font-size: 17px;

            cursor: not-allowed;

            transition: .2s;
        }

        .photo-btn.enabled {

            background: #2349ad;

            color: white;

            cursor: pointer;

            box-shadow:
                0 3px 8px rgba(35, 73, 173, .25);
        }

        .photo-btn.enabled:hover {

            background: #193b92;
        }

        .camera-icon {
            font-size: 22px;
        }

        /* =========================
           RETAKE
        ========================= */

        .retake-btn {

            display: none;

            margin: 12px auto 0;

            border: none;

            background: transparent;

            color: #2349ad;

            font-size: 15px;

            cursor: pointer;

            text-decoration: underline;
        }

        .retake-btn.show {
            display: block;
        }

        /* =========================
           LOADING
        ========================= */

        .loading {

            display: none;

            text-align: center;

            margin-top: 15px;

            color: #2349ad;

            font-size: 15px;
        }

        .loading.show {
            display: block;
        }

        /* =========================
           RESULT
        ========================= */

        .result {

            display: none;

            position: fixed;

            inset: 0;

            background: rgba(0, 0, 0, .7);

            align-items: center;

            justify-content: center;

            z-index: 1000;

            padding: 20px;
        }

        .result.show {

            display: flex;
        }

        .result-card {

            width: 400px;

            max-width: 100%;

            background: white;

            border-radius: 18px;

            padding: 30px;

            text-align: center;

            box-shadow:
                0 15px 50px rgba(0, 0, 0, .3);
        }

        .result-title {

            font-size: 18px;

            color: #555;

            margin-bottom: 10px;
        }

        .queue-number {

            font-size: 65px;

            font-weight: 800;

            color: #2349ad;

            margin: 10px 0;
        }

        .result-service {

            font-size: 18px;

            color: #333;

            margin-bottom: 20px;
        }

        .result-photo {

            width: 120px;

            height: 120px;

            object-fit: cover;

            border-radius: 10px;

            margin-bottom: 20px;
        }

        .close-result {

            width: 100%;

            height: 42px;

            border: none;

            border-radius: 8px;

            background: #2349ad;

            color: white;

            font-size: 16px;

            cursor: pointer;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 800px) {

            .container {

                padding-top: 100px;
            }

            .camera-container {

                padding: 15px;
            }

            .camera-screen {

                height: 260px;
            }

            .service-btn {

                font-size: 15px;

                padding: 10px;
            }

            .photo-btn {

                width: 100%;
            }
        }

        @media (max-width: 500px) {

            .back {

                top: 15px;

                left: 15px;
            }

            .container {

                width: 95%;

                padding-top: 80px;
            }

            .camera-screen {

                height: 230px;
            }

            .services {

                gap: 5px;
            }

            .service-btn {

                width: 100%;
            }

            .queue-number {

                font-size: 52px;
            }
        }
    </style>
</head>

<body>

    <!-- =========================
         BACK
    ========================= -->

    <a href="{{ url('/') }}" class="back">

        <span class="back-arrow">‹</span>

        <span>Back</span>

    </a>


    <!-- =========================
         MAIN
    ========================= -->

    <main class="container">

        <div class="camera-container">

            <!-- CAMERA -->

            <div class="camera-screen">

                <video
                    id="video"
                    autoplay
                    playsinline
                    muted></video>

                <img
                    id="photoPreview"
                    alt="Foto pengunjung">

                <div
                    id="cameraPlaceholder"
                    class="camera-placeholder">
                    Mengaktifkan kamera...
                </div>

                <div
                    id="cameraStatus"
                    class="camera-status">
                    ● Kamera Aktif
                </div>

            </div>


            <!-- ERROR -->

            <div
                id="cameraError"
                class="camera-error">
                Kamera tidak dapat digunakan.
                Silakan izinkan akses kamera pada browser.
            </div>


            <!-- SERVICE -->

            <div class="service-title">
                Silahkan pilih layanan :
            </div>


            <div class="services">

                @foreach ($services as $service)

                <button
                    type="button"
                    class="service-btn"

                    data-service-id="{{ $service->id }}"

                    data-service="{{ $service->name }}"

                    data-service-code="{{ $service->code }}">

                    {{ $service->name }}

                </button>

                @endforeach

            </div>


            <!-- PHOTO BUTTON -->

            <button
                type="button"

                id="photoButton"

                class="photo-btn"

                disabled>

                <span class="camera-icon">
                    📷
                </span>

                <span>
                    Ambil Foto dan Antrian
                </span>

            </button>


            <!-- RETAKE -->

            <button
                type="button"

                id="retakeButton"

                class="retake-btn">
                ↻ Ambil Foto Ulang
            </button>


            <!-- LOADING -->

            <div
                id="loading"
                class="loading">
                ⏳ Menyimpan foto dan membuat nomor antrean...
            </div>

        </div>

    </main>


    <!-- =========================
         CANVAS
    ========================= -->

    <canvas
        id="canvas"
        style="display:none;"></canvas>


    <!-- =========================
         RESULT
    ========================= -->

    <div
        id="result"
        class="result">

        <div class="result-card">

            <div class="result-title">
                Nomor Antrean Anda
            </div>

            <div
                id="queueNumber"
                class="queue-number">
                A-001
            </div>

            <div
                id="resultService"
                class="result-service">
                Pelayanan
            </div>

            <img
                id="resultPhoto"
                class="result-photo"
                alt="Foto Pengunjung">

            <button
                type="button"
                id="closeResult"
                class="close-result">
                Selesai
            </button>

        </div>

    </div>


    <script>
        /*
        ==========================================
        VARIABLE
        ==========================================
        */

        let stream = null;

        let selectedService = null;

        let selectedServiceId = null;

        let photoData = null;


        /*
        ==========================================
        ELEMENT
        ==========================================
        */

        const video =
            document.getElementById('video');

        const photoPreview =
            document.getElementById('photoPreview');

        const canvas =
            document.getElementById('canvas');

        const cameraPlaceholder =
            document.getElementById(
                'cameraPlaceholder'
            );

        const cameraStatus =
            document.getElementById(
                'cameraStatus'
            );

        const cameraError =
            document.getElementById(
                'cameraError'
            );

        const photoButton =
            document.getElementById(
                'photoButton'
            );

        const retakeButton =
            document.getElementById(
                'retakeButton'
            );

        const loading =
            document.getElementById(
                'loading'
            );

        const result =
            document.getElementById(
                'result'
            );

        const queueNumber =
            document.getElementById(
                'queueNumber'
            );

        const resultService =
            document.getElementById(
                'resultService'
            );

        const resultPhoto =
            document.getElementById(
                'resultPhoto'
            );

        const closeResult =
            document.getElementById(
                'closeResult'
            );

        const serviceButtons =
            document.querySelectorAll(
                '.service-btn'
            );


        /*
        ==========================================
        KAMERA
        ==========================================
        */

        async function startCamera() {

            cameraError.classList.remove(
                'show'
            );

            cameraPlaceholder.style.display =
                'flex';

            cameraPlaceholder.innerHTML =
                'Mengaktifkan kamera...';


            try {

                if (
                    !navigator.mediaDevices ||
                    !navigator.mediaDevices.getUserMedia
                ) {

                    throw new Error(
                        'Browser tidak mendukung kamera.'
                    );

                }


                stream =
                    await navigator.mediaDevices
                    .getUserMedia({

                        video: {

                            facingMode: "user",

                            width: {
                                ideal: 1280
                            },

                            height: {
                                ideal: 720
                            }

                        },

                        audio: false

                    });


                video.srcObject =
                    stream;


                await video.play();


                video.style.display =
                    'block';

                photoPreview.style.display =
                    'none';

                cameraPlaceholder.style.display =
                    'none';

                cameraStatus.classList.add(
                    'active'
                );


                console.log(
                    'Kamera aktif'
                );

            } catch (error) {

                console.error(
                    error
                );


                cameraPlaceholder.style.display =
                    'flex';

                cameraPlaceholder.innerHTML =
                    '📷<br>' +
                    'Kamera tidak dapat diakses.<br>' +
                    'Silakan izinkan akses kamera.';


                cameraStatus.classList.remove(
                    'active'
                );


                cameraError.classList.add(
                    'show'
                );

            }

        }


        /*
        ==========================================
        PILIH LAYANAN
        ==========================================
        */

        serviceButtons.forEach(
            button => {

                button.addEventListener(
                    'click',
                    function() {

                        serviceButtons.forEach(
                            btn => {

                                btn.classList.remove(
                                    'active'
                                );

                            }
                        );


                        this.classList.add(
                            'active'
                        );


                        selectedServiceId =
                            this.dataset.serviceId;


                        selectedService =
                            this.dataset.service;


                        photoButton.disabled =
                            false;


                        photoButton.classList.add(
                            'enabled'
                        );


                        console.log(
                            'Service ID:',
                            selectedServiceId
                        );

                        console.log(
                            'Service:',
                            selectedService
                        );

                    }
                );

            }
        );


        /*
        ==========================================
        AMBIL FOTO + KIRIM KE LARAVEL
        ==========================================
        */

        photoButton.addEventListener(
            'click',
            async function() {

                /*
                Cek layanan
                */

                if (!selectedServiceId) {

                    alert(
                        'Silakan pilih layanan terlebih dahulu.'
                    );

                    return;

                }


                /*
                Cek kamera
                */

                if (!stream) {

                    alert(
                        'Kamera belum aktif.'
                    );

                    await startCamera();

                    return;

                }


                /*
                Cek kamera siap
                */

                if (
                    video.videoWidth === 0 ||
                    video.videoHeight === 0
                ) {

                    alert(
                        'Kamera belum siap. Tunggu beberapa detik.'
                    );

                    return;

                }


                /*
                ==================================
                CAPTURE FOTO
                ==================================
                */

                canvas.width =
                    video.videoWidth;

                canvas.height =
                    video.videoHeight;


                const context =
                    canvas.getContext(
                        '2d'
                    );


                context.drawImage(

                    video,

                    0,

                    0,

                    canvas.width,

                    canvas.height

                );


                /*
                ==================================
                CONVERT FOTO
                ==================================
                */

                photoData =
                    canvas.toDataURL(
                        'image/jpeg',
                        0.90
                    );


                /*
                ==================================
                TAMPILKAN FOTO
                ==================================
                */

                photoPreview.src =
                    photoData;

                photoPreview.style.display =
                    'block';

                video.style.display =
                    'none';


                stopCamera();


                /*
                ==================================
                KIRIM KE LARAVEL
                ==================================
                */

                await saveQueue();

            }
        );


        /*
        ==========================================
        SAVE QUEUE
        ==========================================
        */

        async function saveQueue() {

            photoButton.disabled =
                true;

            retakeButton.classList.remove(
                'show'
            );

            loading.classList.add(
                'show'
            );


            try {

                const csrfToken =
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).getAttribute(
                        'content'
                    );


                const response =
                    await fetch(
                        "{{ route('ambil.antrian') }}", {

                            method: 'POST',

                            headers: {

                                'Content-Type': 'application/json',

                                'Accept': 'application/json',

                                'X-CSRF-TOKEN': csrfToken

                            },

                            body: JSON.stringify({

                                service_id: selectedServiceId,

                                photo: photoData

                            })

                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Gagal membuat antrean.'
                    );

                }


                /*
                ==================================
                BERHASIL
                ==================================
                */

                /*
            ==================================
            BERHASIL
            ==================================
            */

            // Simpan URL status antrean di browser
            localStorage.setItem(
                'active_queue_status_url',
                data.queue.status_url
            );

            // Simpan token antrean
            localStorage.setItem(
                'active_queue_token',
                data.queue.public_token
            );

            // Langsung pindah ke halaman status antrean
            window.location.href = data.queue.status_url;

            } catch (error) {

                console.error(
                    error
                );


                alert(
                    'Gagal membuat nomor antrean.\n\n' +
                    error.message
                );


                /*
                Kalau gagal,
                kamera bisa diaktifkan kembali
                */

                photoButton.disabled =
                    false;

                photoButton.classList.add(
                    'enabled'
                );

            } finally {

                loading.classList.remove(
                    'show'
                );

            }

        }


        /*
        ==========================================
        AMBIL FOTO ULANG
        ==========================================
        */

        retakeButton.addEventListener(
            'click',
            function() {

                photoData = null;

                photoPreview.style.display =
                    'none';

                retakeButton.classList.remove(
                    'show'
                );

                startCamera();

            }
        );


        /*
        ==========================================
        STOP CAMERA
        ==========================================
        */

        function stopCamera() {

            if (stream) {

                stream
                    .getTracks()
                    .forEach(
                        track => {

                            track.stop();

                        }
                    );

                stream = null;

            }


            cameraStatus.classList.remove(
                'active'
            );

        }


        /*
        ==========================================
        TUTUP HASIL
        ==========================================
        */

        closeResult.addEventListener(
            'click',
            function() {

                result.classList.remove(
                    'show'
                );

            }
        );


        /*
        ==========================================
        START
        ==========================================
        */

        document.addEventListener(
            'DOMContentLoaded',
            function() {

                startCamera();

            }
        );


        /*
        ==========================================
        STOP SAAT MENINGGALKAN HALAMAN
        ==========================================
        */

        window.addEventListener(
            'beforeunload',
            function() {

                stopCamera();

            }
        );
    </script>

</body>

</html>