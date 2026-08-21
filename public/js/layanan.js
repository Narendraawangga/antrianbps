document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | VARIABLE
        |--------------------------------------------------------------------------
        */

        let stream = null;

        let selectedServiceId = null;

        let selectedServiceName = null;

        let photoData = null;

        let isSubmitting = false;


        /*
        |--------------------------------------------------------------------------
        | ELEMENT
        |--------------------------------------------------------------------------
        */

        const body =
            document.body;


        const submitUrl =
            body.dataset.submitUrl;


        const visitorNameInput =
            document.getElementById(
                'visitorName'
            );


        const nameHint =
            document.getElementById(
                'nameHint'
            );


        const summaryName =
            document.getElementById(
                'summaryName'
            );


        const summaryService =
            document.getElementById(
                'summaryService'
            );


        const serviceButtons =
            document.querySelectorAll(
                '.service-button'
            );


        const video =
            document.getElementById(
                'video'
            );


        const photoPreview =
            document.getElementById(
                'photoPreview'
            );


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


        const canvas =
            document.getElementById(
                'canvas'
            );


        const takeQueueButton =
            document.getElementById(
                'takeQueueButton'
            );


        const loading =
            document.getElementById(
                'loading'
            );


        const resultModal =
            document.getElementById(
                'resultModal'
            );


        const resultQueueNumber =
            document.getElementById(
                'resultQueueNumber'
            );


        const resultVisitorName =
            document.getElementById(
                'resultVisitorName'
            );


        const resultService =
            document.getElementById(
                'resultService'
            );


        const resultPhoto =
            document.getElementById(
                'resultPhoto'
            );


        const finishButton =
            document.getElementById(
                'finishButton'
            );


        const notification =
            document.getElementById(
                'notification'
            );


        const notificationIcon =
            document.getElementById(
                'notificationIcon'
            );


        const notificationText =
            document.getElementById(
                'notificationText'
            );


        /*
        |--------------------------------------------------------------------------
        | CSRF TOKEN
        |--------------------------------------------------------------------------
        */

        const csrfToken =
            document.querySelector(
                'meta[name="csrf-token"]'
            )?.getAttribute(
                'content'
            );


        /*
        |--------------------------------------------------------------------------
        | START CAMERA
        |--------------------------------------------------------------------------
        */

        async function startCamera()
        {
            cameraError.classList.remove(
                'show'
            );


            cameraPlaceholder.style.display =
                'flex';


            cameraPlaceholder.innerHTML =
                `
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
                `;


            try {

                /*
                |--------------------------------------------------------------------------
                | CEK SUPPORT CAMERA
                |--------------------------------------------------------------------------
                */

                if (
                    !navigator.mediaDevices
                    ||
                    !navigator.mediaDevices.getUserMedia
                ) {

                    throw new Error(
                        'Browser tidak mendukung kamera.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | MINTA CAMERA
                |--------------------------------------------------------------------------
                */

                stream =
                    await navigator
                        .mediaDevices
                        .getUserMedia({

                            video: {

                                facingMode:
                                    'user',

                                width: {
                                    ideal: 1280
                                },

                                height: {
                                    ideal: 720
                                }

                            },

                            audio:
                                false
                        });


                /*
                |--------------------------------------------------------------------------
                | TAMPILKAN VIDEO
                |--------------------------------------------------------------------------
                */

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


                updateButtonState();

            } catch (error) {

                console.error(
                    error
                );


                stream =
                    null;


                video.style.display =
                    'none';


                cameraStatus.classList.remove(
                    'active'
                );


                cameraPlaceholder.style.display =
                    'flex';


                cameraPlaceholder.innerHTML =
                    `
                        <div class="camera-placeholder-icon">
                            ⚠️
                        </div>

                        <strong>
                            Kamera tidak dapat diakses
                        </strong>

                        <span>
                            Izinkan akses kamera pada browser,
                            kemudian muat ulang halaman.
                        </span>
                    `;


                cameraError.classList.add(
                    'show'
                );


                updateButtonState();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | STOP CAMERA
        |--------------------------------------------------------------------------
        */

        function stopCamera()
        {
            if (!stream) {
                return;
            }


            stream
                .getTracks()
                .forEach(
                    function (track) {

                        track.stop();

                    }
                );


            stream =
                null;


            cameraStatus.classList.remove(
                'active'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI NAMA
        |--------------------------------------------------------------------------
        */

        function getVisitorName()
        {
            return visitorNameInput
                .value
                .trim();
        }


        function isNameValid()
        {
            return getVisitorName()
                .length >= 2;
        }


        function updateNameState()
        {
            const name =
                getVisitorName();


            if (name.length === 0) {

                summaryName.textContent =
                    'Belum diisi';


                visitorNameInput.classList.remove(
                    'error'
                );


                nameHint.classList.remove(
                    'error'
                );


                nameHint.textContent =
                    'Minimal 2 karakter.';

            } else if (
                name.length < 2
            ) {

                summaryName.textContent =
                    name;


                visitorNameInput.classList.add(
                    'error'
                );


                nameHint.classList.add(
                    'error'
                );


                nameHint.textContent =
                    'Nama minimal 2 karakter.';

            } else {

                summaryName.textContent =
                    name;


                visitorNameInput.classList.remove(
                    'error'
                );


                nameHint.classList.remove(
                    'error'
                );


                nameHint.textContent =
                    'Nama sudah valid.';
            }


            updateButtonState();
        }


        /*
        |--------------------------------------------------------------------------
        | PILIH LAYANAN
        |--------------------------------------------------------------------------
        */

        serviceButtons.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        /*
                        |--------------------------------------------------------------------------
                        | HAPUS ACTIVE
                        |--------------------------------------------------------------------------
                        */

                        serviceButtons.forEach(
                            function (item) {

                                item.classList.remove(
                                    'active'
                                );

                            }
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | SET ACTIVE
                        |--------------------------------------------------------------------------
                        */

                        button.classList.add(
                            'active'
                        );


                        selectedServiceId =
                            button.dataset.serviceId;


                        selectedServiceName =
                            button.dataset.serviceName;


                        summaryService.textContent =
                            selectedServiceName;


                        updateButtonState();
                    }
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE BUTTON
        |--------------------------------------------------------------------------
        */

        function updateButtonState()
        {
            const ready =
                isNameValid()
                &&
                selectedServiceId
                &&
                stream
                &&
                !isSubmitting;


            takeQueueButton.disabled =
                !ready;
        }


        /*
        |--------------------------------------------------------------------------
        | INPUT NAMA
        |--------------------------------------------------------------------------
        */

        visitorNameInput.addEventListener(
            'input',
            updateNameState
        );


        /*
        |--------------------------------------------------------------------------
        | CAPTURE FOTO
        |--------------------------------------------------------------------------
        */

        function capturePhoto()
        {
            if (
                !video.videoWidth
                ||
                !video.videoHeight
            ) {

                throw new Error(
                    'Kamera belum siap. Tunggu beberapa detik.'
                );
            }


            canvas.width =
                video.videoWidth;


            canvas.height =
                video.videoHeight;


            const context =
                canvas.getContext(
                    '2d'
                );


            /*
            |--------------------------------------------------------------------------
            | FOTO TIDAK MIRROR
            |--------------------------------------------------------------------------
            |
            | Video dibuat mirror agar nyaman dilihat.
            | Saat disimpan, foto dikembalikan ke posisi normal.
            |
            */

            context.drawImage(
                video,
                0,
                0,
                canvas.width,
                canvas.height
            );


            photoData =
                canvas.toDataURL(
                    'image/jpeg',
                    0.85
                );


            photoPreview.src =
                photoData;


            photoPreview.style.display =
                'block';


            video.style.display =
                'none';


            stopCamera();


            return photoData;
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL FOTO + ANTREAN
        |--------------------------------------------------------------------------
        */

        takeQueueButton.addEventListener(
            'click',
            async function () {

                if (isSubmitting) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | VALIDASI NAMA
                |--------------------------------------------------------------------------
                */

                if (!isNameValid()) {

                    showNotification(
                        'Silakan masukkan nama pengunjung.',
                        'error'
                    );


                    visitorNameInput.focus();

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | VALIDASI LAYANAN
                |--------------------------------------------------------------------------
                */

                if (!selectedServiceId) {

                    showNotification(
                        'Silakan pilih layanan terlebih dahulu.',
                        'error'
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | VALIDASI CAMERA
                |--------------------------------------------------------------------------
                */

                if (!stream) {

                    showNotification(
                        'Kamera belum aktif.',
                        'error'
                    );


                    await startCamera();

                    return;
                }


                try {

                    /*
                    |--------------------------------------------------------------------------
                    | CAPTURE
                    |--------------------------------------------------------------------------
                    */

                    capturePhoto();


                    /*
                    |--------------------------------------------------------------------------
                    | LOADING
                    |--------------------------------------------------------------------------
                    */

                    isSubmitting =
                        true;


                    takeQueueButton.disabled =
                        true;


                    loading.classList.add(
                        'show'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | REQUEST
                    |--------------------------------------------------------------------------
                    */

                    const response =
                        await fetch(
                            submitUrl,
                            {

                                method:
                                    'POST',

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'Accept':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        csrfToken

                                },

                                body:
                                    JSON.stringify({

                                        visitor_name:
                                            getVisitorName(),

                                        service_id:
                                            selectedServiceId,

                                        photo:
                                            photoData

                                    })
                            }
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | RESPONSE JSON
                    |--------------------------------------------------------------------------
                    */

                    const data =
                        await response.json();


                    /*
                    |--------------------------------------------------------------------------
                    | ERROR
                    |--------------------------------------------------------------------------
                    */

                    if (!response.ok) {

                        let message =
                            data.message
                            ||
                            'Gagal membuat nomor antrean.';


                        /*
                        |--------------------------------------------------------------------------
                        | VALIDATION ERROR LARAVEL
                        |--------------------------------------------------------------------------
                        */

                        if (
                            data.errors
                            &&
                            typeof data.errors ===
                                'object'
                        ) {

                            const firstError =
                                Object.values(
                                    data.errors
                                )[0];


                            if (
                                Array.isArray(
                                    firstError
                                )
                            ) {

                                message =
                                    firstError[0];
                            }
                        }


                        throw new Error(
                            message
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SUCCESS
                    |--------------------------------------------------------------------------
                    */

                    showResult(
                        data.queue
                    );


                    showNotification(
                        'Nomor antrean berhasil dibuat.',
                        'success'
                    );


                } catch (error) {

                    console.error(
                        error
                    );


                    showNotification(
                        error.message
                        ||
                        'Terjadi kesalahan.',
                        'error'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | KALAU GAGAL, CAMERA DIHIDUPKAN LAGI
                    |--------------------------------------------------------------------------
                    */

                    photoData =
                        null;


                    photoPreview.style.display =
                        'none';


                    await startCamera();


                } finally {

                    isSubmitting =
                        false;


                    loading.classList.remove(
                        'show'
                    );


                    updateButtonState();
                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HASIL
        |--------------------------------------------------------------------------
        */

        function showResult(queue)
        {
            resultQueueNumber.textContent =
                queue.number;


            resultVisitorName.textContent =
                queue.visitor_name;


            resultService.textContent =
                queue.service;


            resultPhoto.src =
                queue.photo;


            resultModal.classList.add(
                'show'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SELESAI
        |--------------------------------------------------------------------------
        */

        finishButton.addEventListener(
            'click',
            function () {

                /*
                |--------------------------------------------------------------------------
                | TUTUP MODAL
                |--------------------------------------------------------------------------
                */

                resultModal.classList.remove(
                    'show'
                );


                /*
                |--------------------------------------------------------------------------
                | RESET FORM
                |--------------------------------------------------------------------------
                */

                resetForm();


                /*
                |--------------------------------------------------------------------------
                | KAMERA AKTIF LAGI
                |--------------------------------------------------------------------------
                */

                startCamera();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | RESET FORM
        |--------------------------------------------------------------------------
        */

        function resetForm()
        {
            visitorNameInput.value =
                '';


            selectedServiceId =
                null;


            selectedServiceName =
                null;


            photoData =
                null;


            summaryName.textContent =
                'Belum diisi';


            summaryService.textContent =
                'Belum dipilih';


            nameHint.textContent =
                'Minimal 2 karakter.';


            nameHint.classList.remove(
                'error'
            );


            visitorNameInput.classList.remove(
                'error'
            );


            serviceButtons.forEach(
                function (button) {

                    button.classList.remove(
                        'active'
                    );

                }
            );


            photoPreview.src =
                '';


            photoPreview.style.display =
                'none';


            video.style.display =
                'none';


            updateButtonState();
        }


        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION
        |--------------------------------------------------------------------------
        */

        let notificationTimer =
            null;


        function showNotification(
            message,
            type = 'error'
        )
        {
            clearTimeout(
                notificationTimer
            );


            notificationText.textContent =
                message;


            notification.classList.remove(
                'error',
                'success',
                'show'
            );


            notification.classList.add(
                type
            );


            notificationIcon.textContent =
                type === 'success'
                    ? '✓'
                    : '!';


            requestAnimationFrame(
                function () {

                    notification.classList.add(
                        'show'
                    );

                }
            );


            notificationTimer =
                setTimeout(
                    function () {

                        notification
                            .classList
                            .remove(
                                'show'
                            );

                    },
                    4000
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ENTER PADA INPUT NAMA
        |--------------------------------------------------------------------------
        */

        visitorNameInput.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key ===
                    'Enter'
                ) {

                    event.preventDefault();


                    /*
                    | Kalau nama sudah benar,
                    | arahkan pengguna memilih layanan.
                    */

                    if (
                        isNameValid()
                        &&
                        serviceButtons.length
                    ) {

                        serviceButtons[0]
                            .focus();
                    }
                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | KLIK BACKDROP MODAL
        |--------------------------------------------------------------------------
        |
        | Sengaja tidak menutup modal apabila area gelap diklik.
        | Pengunjung harus klik Selesai agar form benar-benar di-reset.
        |
        */

        resultModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target ===
                    resultModal
                ) {

                    showNotification(
                        'Tekan tombol Selesai untuk melanjutkan.',
                        'error'
                    );
                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | STOP CAMERA KETIKA MENINGGALKAN HALAMAN
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'beforeunload',
            function () {

                stopCamera();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | START
        |--------------------------------------------------------------------------
        */

        updateNameState();

        startCamera();

    }
);