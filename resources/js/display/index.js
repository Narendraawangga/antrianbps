/*
|--------------------------------------------------------------------------
| DISPLAY ANTREAN BPS KOLAKA UTARA
|--------------------------------------------------------------------------
|
| Sistem Display 3 Pelayanan:
|
| - Perpustakaan
| - Konsultasi
| - Rekomendasi
|
*/


/*
|--------------------------------------------------------------------------
| VARIABLE GLOBAL
|--------------------------------------------------------------------------
*/

let availableVoices = [];
let selectedVoice = null;

let youtubePlayer = null;


/*
|--------------------------------------------------------------------------
| DATA SUARA
|--------------------------------------------------------------------------
|
| Semua antrean dengan status "called"
| akan dimasukkan ke daftar ini.
|
*/

let calledQueues = [];


/*
|--------------------------------------------------------------------------
| SUARA YANG SEDANG AKTIF
|--------------------------------------------------------------------------
*/

let activeAnnouncementQueueId = null;


/*
|--------------------------------------------------------------------------
| TIMER PENGULANGAN
|--------------------------------------------------------------------------
*/

let announcementTimer = null;


/*
|--------------------------------------------------------------------------
| TOKEN SIKLUS SUARA
|--------------------------------------------------------------------------
|
| Dipakai supaya callback suara lama tidak
| menjalankan ulang suara setelah status berubah.
|
*/

let announcementCycleToken = 0;


/*
|--------------------------------------------------------------------------
| SIGNATURE DATA
|--------------------------------------------------------------------------
*/

let lastServicesSignature = null;


/*
|--------------------------------------------------------------------------
| LOAD VOICE
|--------------------------------------------------------------------------
*/

function loadVoices() {

    if (
        !('speechSynthesis' in window)
    ) {
        return;
    }


    availableVoices =
        window.speechSynthesis
            .getVoices();


    /*
    |--------------------------------------------------------------------------
    | PRIORITAS BAHASA INDONESIA
    |--------------------------------------------------------------------------
    */

    selectedVoice =
        availableVoices.find(
            (voice) =>
                voice.lang === 'id-ID'
        )

        ??

        availableVoices.find(
            (voice) =>
                voice.lang
                    .toLowerCase()
                    .startsWith('id')
        )

        ??

        null;
}


/*
|--------------------------------------------------------------------------
| LOAD VOICE SAAT HALAMAN DIBUKA
|--------------------------------------------------------------------------
*/

loadVoices();


if (
    'speechSynthesis' in window
) {

    window.speechSynthesis
        .addEventListener(
            'voiceschanged',
            loadVoices
        );
}


/*
|--------------------------------------------------------------------------
| JAM WITA
|--------------------------------------------------------------------------
*/

function updateClock() {

    const now =
        new Date();


    const timeFormatter =
        new Intl.DateTimeFormat(
            'id-ID',
            {
                timeZone:
                    'Asia/Makassar',

                hour:
                    '2-digit',

                minute:
                    '2-digit',

                second:
                    '2-digit',

                hour12:
                    false,
            }
        );


    const dateFormatter =
        new Intl.DateTimeFormat(
            'id-ID',
            {
                timeZone:
                    'Asia/Makassar',

                weekday:
                    'long',

                day:
                    '2-digit',

                month:
                    'long',

                year:
                    'numeric',
            }
        );


    const timeElement =
        document.getElementById(
            'displayTime'
        );


    const dateElement =
        document.getElementById(
            'displayDate'
        );


    if (timeElement) {

        timeElement.textContent =
            timeFormatter.format(
                now
            );
    }


    if (dateElement) {

        dateElement.textContent =
            dateFormatter.format(
                now
            );
    }
}


/*
|--------------------------------------------------------------------------
| UPDATE SATU KARTU PELAYANAN
|--------------------------------------------------------------------------
*/

function updateServiceCard(
    service
) {

    const container =
        document.getElementById(
            'serviceDisplayContainer'
        );


    if (!container) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CARI CARD SESUAI SERVICE ID
    |--------------------------------------------------------------------------
    */

    const card =
        container.querySelector(
            `[data-service-id="${service.service_id}"]`
        );


    if (!card) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | NAMA PELAYANAN
    |--------------------------------------------------------------------------
    */

    const serviceName =
        card.querySelector(
            '[data-service-name]'
        );


    if (serviceName) {

        serviceName.textContent =
            service.service_name;
    }


    /*
    |--------------------------------------------------------------------------
    | ELEMENT NOMOR
    |--------------------------------------------------------------------------
    */

    const numberElement =
        card.querySelector(
            '[data-current-number]'
        );


    /*
    |--------------------------------------------------------------------------
    | ELEMENT STATUS
    |--------------------------------------------------------------------------
    */

    const statusElement =
        card.querySelector(
            '[data-current-status]'
        );


    /*
    |--------------------------------------------------------------------------
    | ANTREAN AKTIF
    |--------------------------------------------------------------------------
    */

    const currentQueue =
        service.current_queue;


    if (currentQueue) {

        /*
        |--------------------------------------------------------------------------
        | NOMOR
        |--------------------------------------------------------------------------
        */

        if (numberElement) {

            numberElement.textContent =
                currentQueue.number;
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (statusElement) {

            statusElement.textContent =
                currentQueue.status_label;


            statusElement.classList.remove(
                'status-called',
                'status-serving',
                'status-empty'
            );


            statusElement.classList.add(
                `status-${currentQueue.status}`
            );
        }

    } else {

        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA ANTREAN AKTIF
        |--------------------------------------------------------------------------
        */

        if (numberElement) {

            numberElement.textContent =
                '-';
        }


        if (statusElement) {

            statusElement.textContent =
                'Menunggu Panggilan';


            statusElement.classList.remove(
                'status-called',
                'status-serving'
            );


            statusElement.classList.add(
                'status-empty'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ANTREAN BERIKUTNYA
    |--------------------------------------------------------------------------
    */

    updateNextQueues(
        card,
        service.next_queues
    );
}


/*
|--------------------------------------------------------------------------
| UPDATE ANTREAN BERIKUTNYA
|--------------------------------------------------------------------------
*/

function updateNextQueues(
    card,
    queues
) {

    const container =
        card.querySelector(
            '[data-next-list]'
        );


    if (!container) {
        return;
    }


    container.replaceChildren();


    /*
    |--------------------------------------------------------------------------
    | PASTIKAN ARRAY
    |--------------------------------------------------------------------------
    */

    const nextQueues =
        Array.isArray(queues)
            ? queues
            : [];


    /*
    |--------------------------------------------------------------------------
    | TIDAK ADA ANTREAN MENUNGGU
    |--------------------------------------------------------------------------
    */

    if (
        nextQueues.length === 0
    ) {

        const empty =
            document.createElement(
                'div'
            );


        empty.className =
            'service-next-empty';


        empty.textContent =
            'Belum ada antrean menunggu';


        container.appendChild(
            empty
        );


        return;
    }


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN ANTREAN
    |--------------------------------------------------------------------------
    */

    nextQueues.forEach(
        (queue) => {

            const item =
                document.createElement(
                    'div'
                );


            item.className =
                'service-next-item';


            const number =
                document.createElement(
                    'span'
                );


            number.className =
                'service-next-number';


            number.textContent =
                queue.number;


            item.appendChild(
                number
            );


            container.appendChild(
                item
            );
        }
    );
}


/*
|--------------------------------------------------------------------------
| UBAH NOMOR ANTREAN UNTUK SUARA
|--------------------------------------------------------------------------
|
| Contoh:
|
| A-001
|
| menjadi:
|
| A nol nol satu
|
*/

function queueNumberToSpeech(
    queueNumber
) {

    if (!queueNumber) {
        return '';
    }


    const numbers = {

        '0': 'nol',
        '1': 'satu',
        '2': 'dua',
        '3': 'tiga',
        '4': 'empat',
        '5': 'lima',
        '6': 'enam',
        '7': 'tujuh',
        '8': 'delapan',
        '9': 'sembilan',

    };


    return String(queueNumber)

        .replace(
            /-/g,
            ''
        )

        .split('')

        .map(
            (character) => {

                return (
                    numbers[character]
                    ??
                    character
                );
            }
        )

        .join(' ')

        .replace(
            /\s+/g,
            ' '
        )

        .trim();
}


/*
|--------------------------------------------------------------------------
| BACA ANTREAN
|--------------------------------------------------------------------------
*/

function announceQueue(
    queue,
    onFinished = null
) {

    if (
        !('speechSynthesis' in window)
    ) {

        if (onFinished) {
            onFinished();
        }

        return;
    }


    const queueText =
        queueNumberToSpeech(
            queue.number
        );


    /*
    |--------------------------------------------------------------------------
    | KALIMAT PANGGILAN
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | Nomor antrean A nol nol satu.
    | Silakan menuju loket Pelayanan Perpustakaan.
    |
    */

    const message =
        `Nomor antrean ${queueText}. ` +
        `Silakan menuju loket ${queue.service}.`;


    const speech =
        new SpeechSynthesisUtterance(
            message
        );


    speech.lang =
        'id-ID';


    if (selectedVoice) {

        speech.voice =
            selectedVoice;
    }


    /*
    |--------------------------------------------------------------------------
    | KECEPATAN SUARA
    |--------------------------------------------------------------------------
    */

    speech.rate =
        0.92;


    speech.pitch =
        1;


    speech.volume =
        1;


    /*
    |--------------------------------------------------------------------------
    | SELESAI BICARA
    |--------------------------------------------------------------------------
    */

    speech.onend =
        () => {

            if (onFinished) {
                onFinished();
            }
        };


    /*
    |--------------------------------------------------------------------------
    | ERROR SPEECH
    |--------------------------------------------------------------------------
    */

    speech.onerror =
        () => {

            if (onFinished) {
                onFinished();
            }
        };


    window.speechSynthesis
        .resume();


    window.speechSynthesis
        .speak(
            speech
        );
}


/*
|--------------------------------------------------------------------------
| HENTIKAN SUARA
|--------------------------------------------------------------------------
*/

function stopAnnouncementLoop() {

    /*
    |--------------------------------------------------------------------------
    | MATIKAN CALLBACK LAMA
    |--------------------------------------------------------------------------
    */

    announcementCycleToken++;


    /*
    |--------------------------------------------------------------------------
    | RESET ID
    |--------------------------------------------------------------------------
    */

    activeAnnouncementQueueId =
        null;


    /*
    |--------------------------------------------------------------------------
    | TIMER
    |--------------------------------------------------------------------------
    */

    if (
        announcementTimer !== null
    ) {

        clearTimeout(
            announcementTimer
        );


        announcementTimer =
            null;
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL SPEECH
    |--------------------------------------------------------------------------
    */

    if (
        'speechSynthesis' in window
    ) {

        window.speechSynthesis
            .cancel();
    }
}


/*
|--------------------------------------------------------------------------
| JALANKAN ANTREAN SUARA BERIKUTNYA
|--------------------------------------------------------------------------
|
| Jika hanya satu antrean dipanggil:
|
| A001
| 4 detik
| A001
| 4 detik
| ...
|
|
| Jika tiga pelayanan memanggil bersamaan:
|
| A001
| 4 detik
| B001
| 4 detik
| D001
| 4 detik
| A001
| ...
|
| Jadi suara tidak saling bertabrakan.
|
*/

function runNextAnnouncement() {

    /*
    |--------------------------------------------------------------------------
    | TIDAK ADA YANG DIPANGGIL
    |--------------------------------------------------------------------------
    */

    if (
        calledQueues.length === 0
    ) {

        stopAnnouncementLoop();

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CARI POSISI ANTREAN YANG TERAKHIR DIBACA
    |--------------------------------------------------------------------------
    */

    let nextIndex = 0;


    if (
        activeAnnouncementQueueId !== null
    ) {

        const currentIndex =
            calledQueues.findIndex(
                (queue) =>
                    queue.id ===
                    activeAnnouncementQueueId
            );


        /*
        |--------------------------------------------------------------------------
        | LANJUT KE ANTREAN BERIKUTNYA
        |--------------------------------------------------------------------------
        */

        if (
            currentIndex !== -1
        ) {

            nextIndex =
                (
                    currentIndex + 1
                )
                %
                calledQueues.length;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ANTREAN YANG AKAN DIBACA
    |--------------------------------------------------------------------------
    */

    const queue =
        calledQueues[nextIndex];


    if (!queue) {
        return;
    }


    activeAnnouncementQueueId =
        queue.id;


    /*
    |--------------------------------------------------------------------------
    | TOKEN SIKLUS SAAT INI
    |--------------------------------------------------------------------------
    */

    const currentToken =
        announcementCycleToken;


    /*
    |--------------------------------------------------------------------------
    | BACA
    |--------------------------------------------------------------------------
    */

    announceQueue(
        queue,
        () => {

            /*
            |--------------------------------------------------------------------------
            | CALLBACK SUDAH KADALUWARSA
            |--------------------------------------------------------------------------
            */

            if (
                currentToken !==
                announcementCycleToken
            ) {

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | JIKA SUDAH TIDAK CALLED JANGAN ULANGI
            |--------------------------------------------------------------------------
            */

            const stillCalled =
                calledQueues.some(
                    (item) =>
                        item.id ===
                        queue.id
                );


            /*
            |--------------------------------------------------------------------------
            | TUNGGU 4 DETIK
            |--------------------------------------------------------------------------
            */

            announcementTimer =
                setTimeout(
                    () => {

                        /*
                        |--------------------------------------------------------------------------
                        | TOKEN MASIH VALID
                        |--------------------------------------------------------------------------
                        */

                        if (
                            currentToken !==
                            announcementCycleToken
                        ) {

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Jika antrean sudah berubah serving,
                        | tetap lanjutkan ke antrean called lain.
                        |--------------------------------------------------------------------------
                        */

                        if (!stillCalled) {

                            activeAnnouncementQueueId =
                                null;
                        }


                        runNextAnnouncement();

                    },
                    4000
                );
        }
    );
}


/*
|--------------------------------------------------------------------------
| SINKRONISASI SUARA
|--------------------------------------------------------------------------
*/

function syncAnnouncements(
    newCalledQueues
) {

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA TERBARU
    |--------------------------------------------------------------------------
    */

    calledQueues =
        newCalledQueues;


    /*
    |--------------------------------------------------------------------------
    | TIDAK ADA CALLED
    |--------------------------------------------------------------------------
    */

    if (
        calledQueues.length === 0
    ) {

        stopAnnouncementLoop();

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | BELUM ADA SUARA BERJALAN
    |--------------------------------------------------------------------------
    */

    if (
        activeAnnouncementQueueId === null
    ) {

        announcementCycleToken++;

        runNextAnnouncement();

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CEK ANTREAN YANG SEDANG DIBACA
    |--------------------------------------------------------------------------
    */

    const activeStillCalled =
        calledQueues.some(
            (queue) =>
                queue.id ===
                activeAnnouncementQueueId
        );


    /*
    |--------------------------------------------------------------------------
    | STATUS SUDAH BERUBAH
    |
    | Contoh:
    |
    | called -> serving
    |
    | Suara langsung dihentikan.
    |--------------------------------------------------------------------------
    */

    if (!activeStillCalled) {

        stopAnnouncementLoop();


        /*
        |--------------------------------------------------------------------------
        | MASIH ADA CALLED LAIN
        |--------------------------------------------------------------------------
        */

        if (
            calledQueues.length > 0
        ) {

            announcementCycleToken++;

            runNextAnnouncement();
        }
    }
}


/*
|--------------------------------------------------------------------------
| BUAT SIGNATURE DATA
|--------------------------------------------------------------------------
*/

function createServicesSignature(
    services
) {

    return JSON.stringify(

        services.map(
            (service) => {

                return {

                    service_id:
                        service.service_id,

                    current_queue:
                        service.current_queue
                            ? {

                                id:
                                    service.current_queue.id,

                                number:
                                    service.current_queue.number,

                                status:
                                    service.current_queue.status,

                                status_label:
                                    service.current_queue.status_label,

                            }
                            : null,

                    next_queues:
                        Array.isArray(
                            service.next_queues
                        )
                            ? service.next_queues.map(
                                (queue) => {

                                    return {

                                        id:
                                            queue.id,

                                        number:
                                            queue.number,

                                    };
                                }
                            )
                            : [],
                };
            }
        )
    );
}


/*
|--------------------------------------------------------------------------
| FETCH DATA DISPLAY
|--------------------------------------------------------------------------
*/

async function fetchQueues() {

    const container =
        document.getElementById(
            'serviceDisplayContainer'
        );


    if (!container) {
        return;
    }


    const url =
        container.dataset.url;


    if (!url) {
        return;
    }


    try {

        /*
        |--------------------------------------------------------------------------
        | REQUEST
        |--------------------------------------------------------------------------
        */

        const response =
            await fetch(
                url,
                {

                    headers: {

                        Accept:
                            'application/json',

                    },

                    cache:
                        'no-store',

                }
            );


        /*
        |--------------------------------------------------------------------------
        | ERROR HTTP
        |--------------------------------------------------------------------------
        */

        if (!response.ok) {

            throw new Error(
                'Gagal mengambil data antrean.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | JSON
        |--------------------------------------------------------------------------
        */

        const data =
            await response.json();


        if (!data.success) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DATA SERVICES
        |--------------------------------------------------------------------------
        */

        const services =
            Array.isArray(
                data.services
            )
                ? data.services
                : [];


        /*
        |--------------------------------------------------------------------------
        | UPDATE CARD HANYA JIKA DATA BERUBAH
        |--------------------------------------------------------------------------
        */

        const signature =
            createServicesSignature(
                services
            );


        if (
            signature !==
            lastServicesSignature
        ) {

            lastServicesSignature =
                signature;


            services.forEach(
                (service) => {

                    updateServiceCard(
                        service
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KUMPULKAN SEMUA STATUS CALLED
        |--------------------------------------------------------------------------
        */

        const newCalledQueues = [];


        services.forEach(
            (service) => {

                const currentQueue =
                    service.current_queue;


                if (
                    currentQueue &&
                    currentQueue.status ===
                        'called'
                ) {

                    newCalledQueues.push({

                        id:
                            currentQueue.id,

                        number:
                            currentQueue.number,

                        serviceId:
                            service.service_id,

                        service:
                            service.service_name,

                    });
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SINKRON SUARA
        |--------------------------------------------------------------------------
        */

        syncAnnouncements(
            newCalledQueues
        );

    } catch (error) {

        console.error(
            'Display antrean:',
            error
        );
    }
}


/*
|--------------------------------------------------------------------------
| JAM
|--------------------------------------------------------------------------
*/

updateClock();


setInterval(
    updateClock,
    1000
);


/*
|--------------------------------------------------------------------------
| AUTO UPDATE DISPLAY
|--------------------------------------------------------------------------
|
| Cek database setiap 2 detik.
|
*/

fetchQueues();


setInterval(
    fetchQueues,
    2000
);


/*
|--------------------------------------------------------------------------
| YOUTUBE API
|--------------------------------------------------------------------------
*/

function loadYouTubeAPI() {

    const playerElement =
        document.getElementById(
            'bpsYoutubePlayer'
        );


    if (!playerElement) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | API SUDAH TERSEDIA
    |--------------------------------------------------------------------------
    */

    if (
        window.YT &&
        window.YT.Player
    ) {

        createYouTubePlayer();

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | SCRIPT SUDAH DIMUAT
    |--------------------------------------------------------------------------
    */

    if (
        document.querySelector(
            'script[src="https://www.youtube.com/iframe_api"]'
        )
    ) {

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD SCRIPT YOUTUBE
    |--------------------------------------------------------------------------
    */

    const script =
        document.createElement(
            'script'
        );


    script.src =
        'https://www.youtube.com/iframe_api';


    script.async =
        true;


    document.head.appendChild(
        script
    );
}


/*
|--------------------------------------------------------------------------
| YOUTUBE READY
|--------------------------------------------------------------------------
*/

window.onYouTubeIframeAPIReady =
    function () {

        createYouTubePlayer();
    };


/*
|--------------------------------------------------------------------------
| CREATE YOUTUBE PLAYER
|--------------------------------------------------------------------------
*/

function createYouTubePlayer() {

    const playerElement =
        document.getElementById(
            'bpsYoutubePlayer'
        );


    if (
        !playerElement ||
        youtubePlayer
    ) {

        return;
    }


    const videoId =
        playerElement.dataset.videoId;


    if (!videoId) {

        console.error(
            'Video ID YouTube tidak ditemukan.'
        );

        return;
    }


    youtubePlayer =
        new YT.Player(
            'bpsYoutubePlayer',
            {

                videoId:
                    videoId,


                playerVars: {

                    autoplay:
                        1,

                    controls:
                        1,

                    loop:
                        1,

                    playlist:
                        videoId,

                    rel:
                        0,

                    playsinline:
                        1,

                },


                events: {


                    /*
                    |--------------------------------------------------------------------------
                    | READY
                    |--------------------------------------------------------------------------
                    */

                    onReady:
                        function (event) {

                            /*
                            |--------------------------------------------------------------------------
                            | MUTE
                            |--------------------------------------------------------------------------
                            |
                            | Video dimute agar autoplay tidak diblokir browser
                            | dan tidak mengganggu suara panggilan antrean.
                            |
                            */

                            event.target.mute();


                            event.target.setVolume(
                                10
                            );


                            event.target.playVideo();
                        },


                    /*
                    |--------------------------------------------------------------------------
                    | VIDEO SELESAI
                    |--------------------------------------------------------------------------
                    */

                    onStateChange:
                        function (event) {

                            if (
                                event.data ===
                                YT.PlayerState.ENDED
                            ) {

                                event.target.seekTo(
                                    0
                                );


                                event.target.playVideo();
                            }
                        },

                },

            }
        );
}


/*
|--------------------------------------------------------------------------
| JALANKAN YOUTUBE
|--------------------------------------------------------------------------
*/

loadYouTubeAPI();