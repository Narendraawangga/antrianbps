/*
|--------------------------------------------------------------------------
| DISPLAY ANTREAN BPS KOLAKA UTARA
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| VARIABLE GLOBAL
|--------------------------------------------------------------------------
*/

let availableVoices = [];
let selectedVoice = null;

let activeAnnouncementQueueId = null;
let announcementTimer = null;

let youtubePlayer = null;

let lastQueuesSignature = null;
let lastNextQueuesSignature = null;


/*
|--------------------------------------------------------------------------
| LOAD VOICE
|--------------------------------------------------------------------------
*/

function loadVoices()
{
    if (!('speechSynthesis' in window)) {
        return;
    }

    availableVoices =
        window.speechSynthesis.getVoices();

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

if ('speechSynthesis' in window) {

    window.speechSynthesis.addEventListener(
        'voiceschanged',
        loadVoices
    );

}


/*
|--------------------------------------------------------------------------
| JAM WITA
|--------------------------------------------------------------------------
*/

function updateClock()
{
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
                    false
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
                    'numeric'
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
| NORMALISASI DATA DARI CONTROLLER
|--------------------------------------------------------------------------
|
| Controller sekarang mengirim:
|
| services
|   ├── service_name
|   ├── current_queue
|   └── next_queues
|
| Di sini kita ubah kembali menjadi array yang mudah
| digunakan oleh tampilan.
|
*/

function normalizeDisplayData(
    services
)
{
    const activeQueues = [];
    const nextQueues = [];


    services.forEach(
        (service) => {

            /*
            |--------------------------------------------------------------------------
            | ANTREAN AKTIF
            |--------------------------------------------------------------------------
            */

            if (
                service.current_queue
            ) {

                activeQueues.push({

                    ...service.current_queue,

                    service:
                        service.service_name,

                    service_code:
                        service.service_code

                });

            }


            /*
            |--------------------------------------------------------------------------
            | ANTREAN MENUNGGU
            |--------------------------------------------------------------------------
            */

            if (
                Array.isArray(
                    service.next_queues
                )
            ) {

                service.next_queues.forEach(
                    (queue) => {

                        nextQueues.push({

                            ...queue,

                            service:
                                service.service_name,

                            service_code:
                                service.service_code

                        });

                    }
                );

            }

        }
    );


    return {
        activeQueues,
        nextQueues
    };
}


/*
|--------------------------------------------------------------------------
| RENDER ANTREAN AKTIF
|--------------------------------------------------------------------------
*/

function renderQueues(
    queues
)
{
    const container =
        document.getElementById(
            'queueContainer'
        );


    if (!container) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | BERSIHKAN
    |--------------------------------------------------------------------------
    */

    container.replaceChildren();


    /*
    |--------------------------------------------------------------------------
    | BELUM ADA ANTREAN
    |--------------------------------------------------------------------------
    */

    if (
        queues.length === 0
    ) {

        const empty =
            document.createElement(
                'div'
            );


        empty.className =
            'queue-empty';


        empty.innerHTML = `

            <div class="queue-empty-icon">
                🎫
            </div>

            <h2>
                Belum Ada Antrean Aktif
            </h2>

            <p>
                Nama dan nomor antrean akan tampil
                ketika petugas melakukan pemanggilan.
            </p>

        `;


        container.appendChild(
            empty
        );


        return;
    }


    /*
    |--------------------------------------------------------------------------
    | GRID
    |--------------------------------------------------------------------------
    */

    const grid =
        document.createElement(
            'div'
        );


    grid.className =
        'queue-grid';


    /*
    |--------------------------------------------------------------------------
    | BUAT CARD
    |--------------------------------------------------------------------------
    */

    queues.forEach(
        (queue) => {

            /*
            |--------------------------------------------------------------------------
            | CARD
            |--------------------------------------------------------------------------
            */

            const card =
                document.createElement(
                    'div'
                );


            card.className =
                'queue-card';


            card.dataset.queueId =
                queue.id;


            /*
            |--------------------------------------------------------------------------
            | NOMOR ANTREAN
            |--------------------------------------------------------------------------
            */

            const number =
                document.createElement(
                    'div'
                );


            number.className =
                'queue-number';


            number.textContent =
                queue.number;


            /*
            |--------------------------------------------------------------------------
            | NAMA PENGUNJUNG
            |--------------------------------------------------------------------------
            */

            const visitorName =
                document.createElement(
                    'div'
                );


            visitorName.className =
                'queue-visitor-name';


            visitorName.textContent =
                queue.visitor_name
                    ? queue.visitor_name
                        .toUpperCase()
                    : 'NAMA PENGUNJUNG';


            /*
            |--------------------------------------------------------------------------
            | LAYANAN
            |--------------------------------------------------------------------------
            */

            const service =
                document.createElement(
                    'div'
                );


            service.className =
                'queue-service';


            service.textContent =
                queue.service;


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            const status =
                document.createElement(
                    'div'
                );


            status.className =
                `queue-status status-${queue.status}`;


            status.textContent =
                queue.status_label;


            /*
            |--------------------------------------------------------------------------
            | MASUKKAN KE CARD
            |--------------------------------------------------------------------------
            */

            card.appendChild(
                number
            );


            card.appendChild(
                visitorName
            );


            card.appendChild(
                service
            );


            card.appendChild(
                status
            );


            grid.appendChild(
                card
            );

        }
    );


    container.appendChild(
        grid
    );
}


/*
|--------------------------------------------------------------------------
| RENDER ANTREAN BERIKUTNYA
|--------------------------------------------------------------------------
*/

function renderNextQueues(
    queues
)
{
    const container =
        document.getElementById(
            'nextQueueList'
        );


    if (!container) {
        return;
    }


    container.replaceChildren();


    /*
    |--------------------------------------------------------------------------
    | TIDAK ADA YANG MENUNGGU
    |--------------------------------------------------------------------------
    */

    if (
        queues.length === 0
    ) {

        const empty =
            document.createElement(
                'div'
            );


        empty.className =
            'next-queue-empty';


        empty.textContent =
            'Tidak ada antrean menunggu';


        container.appendChild(
            empty
        );


        return;
    }


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN ANTREAN MENUNGGU
    |--------------------------------------------------------------------------
    */

    queues.forEach(
        (queue) => {

            /*
            |--------------------------------------------------------------------------
            | ITEM
            |--------------------------------------------------------------------------
            */

            const item =
                document.createElement(
                    'div'
                );


            item.className =
                'next-queue-item';


            /*
            |--------------------------------------------------------------------------
            | NOMOR
            |--------------------------------------------------------------------------
            */

            const number =
                document.createElement(
                    'strong'
                );


            number.className =
                'next-queue-number';


            number.textContent =
                queue.number;


            /*
            |--------------------------------------------------------------------------
            | DETAIL
            |--------------------------------------------------------------------------
            */

            const detail =
                document.createElement(
                    'div'
                );


            detail.className =
                'next-queue-detail';


            /*
            |--------------------------------------------------------------------------
            | NAMA
            |--------------------------------------------------------------------------
            */

            const visitorName =
                document.createElement(
                    'span'
                );


            visitorName.className =
                'next-queue-name';


            visitorName.textContent =
                queue.visitor_name
                    ? queue.visitor_name
                    : 'Nama pengunjung';


            /*
            |--------------------------------------------------------------------------
            | LAYANAN
            |--------------------------------------------------------------------------
            */

            const service =
                document.createElement(
                    'small'
                );


            service.className =
                'next-queue-service';


            service.textContent =
                queue.service;


            /*
            |--------------------------------------------------------------------------
            | MASUKKAN
            |--------------------------------------------------------------------------
            */

            detail.appendChild(
                visitorName
            );


            detail.appendChild(
                service
            );


            item.appendChild(
                number
            );


            item.appendChild(
                detail
            );


            container.appendChild(
                item
            );

        }
    );
}


/*
|--------------------------------------------------------------------------
| NOMOR ANTREAN UNTUK SUARA
|--------------------------------------------------------------------------
|
| A-001
|
| Menjadi:
|
| A nol nol satu
|
*/

function queueNumberToSpeech(
    queueNumber
)
{
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
        '9': 'sembilan'

    };


    return queueNumber

        .replace(
            '-',
            ' '
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
)
{
    if (
        !(
            'speechSynthesis'
            in window
        )
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | NOMOR
    |--------------------------------------------------------------------------
    */

    const queueText =
        queueNumberToSpeech(
            queue.number
        );


    /*
    |--------------------------------------------------------------------------
    | NAMA
    |--------------------------------------------------------------------------
    */

    const visitorName =
        queue.visitor_name
            ? queue.visitor_name
            : 'pengunjung';


    /*
    |--------------------------------------------------------------------------
    | PESAN SUARA
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | Atas nama Narendra Awangga.
    | Nomor antrean A nol nol satu.
    | Silakan menuju Pelayanan Perpustakaan.
    |
    */

    const message =
        `Atas nama ${visitorName}. ` +
        `Nomor antrean ${queueText}. ` +
        `Silakan menuju ${queue.service}.`;


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


    speech.rate =
        0.92;


    speech.pitch =
        1;


    speech.volume =
        1;


    /*
    |--------------------------------------------------------------------------
    | SELESAI
    |--------------------------------------------------------------------------
    */

    speech.onend =
        () => {

            if (onFinished) {

                onFinished();

            }

        };


    speech.onerror =
        () => {

            if (onFinished) {

                onFinished();

            }

        };


    window
        .speechSynthesis
        .resume();


    window
        .speechSynthesis
        .speak(
            speech
        );
}


/*
|--------------------------------------------------------------------------
| HENTIKAN PENGUMUMAN
|--------------------------------------------------------------------------
*/

function stopAnnouncementLoop()
{
    /*
    |--------------------------------------------------------------------------
    | RESET ID
    |--------------------------------------------------------------------------
    */

    activeAnnouncementQueueId =
        null;


    /*
    |--------------------------------------------------------------------------
    | HENTIKAN TIMER
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
    | HENTIKAN SUARA
    |--------------------------------------------------------------------------
    */

    if (
        'speechSynthesis'
        in window
    ) {

        window
            .speechSynthesis
            .cancel();

    }
}


/*
|--------------------------------------------------------------------------
| MULAI PENGUMUMAN BERULANG
|--------------------------------------------------------------------------
*/

function startAnnouncementLoop(
    queue
)
{
    /*
    |--------------------------------------------------------------------------
    | SUDAH MEMANGGIL ANTREAN INI
    |--------------------------------------------------------------------------
    */

    if (
        activeAnnouncementQueueId
        === queue.id
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | HENTIKAN YANG LAMA
    |--------------------------------------------------------------------------
    */

    stopAnnouncementLoop();


    /*
    |--------------------------------------------------------------------------
    | SIMPAN ANTREAN
    |--------------------------------------------------------------------------
    */

    activeAnnouncementQueueId =
        queue.id;


    /*
    |--------------------------------------------------------------------------
    | ULANGI PENGUMUMAN
    |--------------------------------------------------------------------------
    */

    function repeatAnnouncement()
    {
        if (
            activeAnnouncementQueueId
            !== queue.id
        ) {

            return;

        }


        announceQueue(
            queue,

            () => {

                /*
                |--------------------------------------------------------------------------
                | ANTREAN SUDAH BERUBAH
                |--------------------------------------------------------------------------
                */

                if (
                    activeAnnouncementQueueId
                    !== queue.id
                ) {

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | ULANG 4 DETIK SETELAH SUARA SELESAI
                |--------------------------------------------------------------------------
                */

                announcementTimer =
                    setTimeout(
                        repeatAnnouncement,
                        4000
                    );

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PANGGIL PERTAMA LANGSUNG
    |--------------------------------------------------------------------------
    */

    repeatAnnouncement();
}


/*
|--------------------------------------------------------------------------
| SIGNATURE DATA
|--------------------------------------------------------------------------
|
| Agar DOM tidak dirender ulang setiap polling
| apabila data tidak berubah.
|
*/

function createQueueSignature(
    queues
)
{
    return JSON.stringify(

        queues.map(
            (queue) => [

                queue.id,

                queue.status,

                queue.number,

                queue.visitor_name
                    ?? null,

                queue.service,

                queue.called_at
                    ?? null

            ]
        )

    );
}


/*
|--------------------------------------------------------------------------
| FETCH DATA DISPLAY
|--------------------------------------------------------------------------
*/

async function fetchQueues()
{
    const container =
        document.getElementById(
            'queueContainer'
        );


    if (!container) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | URL /display/data
    |--------------------------------------------------------------------------
    */

    const url =
        container.dataset.url;


    if (!url) {
        return;
    }


    try {

        /*
        |--------------------------------------------------------------------------
        | FETCH
        |--------------------------------------------------------------------------
        */

        const response =
            await fetch(
                url,
                {
                    headers: {

                        Accept:
                            'application/json'

                    },

                    cache:
                        'no-store'
                }
            );


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
        | AMBIL SERVICES
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
        | NORMALISASI
        |--------------------------------------------------------------------------
        */

        const {
            activeQueues,
            nextQueues
        } =
            normalizeDisplayData(
                services
            );


        /*
        |--------------------------------------------------------------------------
        | UPDATE ANTREAN AKTIF
        |--------------------------------------------------------------------------
        */

        const currentSignature =
            createQueueSignature(
                activeQueues
            );


        if (
            currentSignature
            !== lastQueuesSignature
        ) {

            lastQueuesSignature =
                currentSignature;


            renderQueues(
                activeQueues
            );

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE ANTREAN BERIKUTNYA
        |--------------------------------------------------------------------------
        */

        const nextSignature =
            createQueueSignature(
                nextQueues
            );


        if (
            nextSignature
            !== lastNextQueuesSignature
        ) {

            lastNextQueuesSignature =
                nextSignature;


            renderNextQueues(
                nextQueues
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CARI YANG SEDANG DIPANGGIL
        |--------------------------------------------------------------------------
        */

        const calledQueue =
            activeQueues.find(
                (queue) =>
                    queue.status
                    === 'called'
            );


        /*
        |--------------------------------------------------------------------------
        | SUARA
        |--------------------------------------------------------------------------
        */

        if (calledQueue) {

            startAnnouncementLoop(
                calledQueue
            );

        } else {

            stopAnnouncementLoop();

        }


    } catch (error) {

        console.error(
            'Display antrean:',
            error
        );

    }
}


/*
|--------------------------------------------------------------------------
| JAM REAL-TIME
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
| Cek data setiap 2 detik.
|
*/

fetchQueues();


setInterval(
    fetchQueues,
    2000
);


/*
|--------------------------------------------------------------------------
| YOUTUBE
|--------------------------------------------------------------------------
*/

function loadYouTubeAPI()
{
    const playerElement =
        document.getElementById(
            'bpsYoutubePlayer'
        );


    if (!playerElement) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | API SUDAH ADA
    |--------------------------------------------------------------------------
    */

    if (
        window.YT
        &&
        window.YT.Player
    ) {

        createYouTubePlayer();

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | SCRIPT SUDAH PERNAH DIMASUKKAN
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
    | LOAD API
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
| YOUTUBE API READY
|--------------------------------------------------------------------------
*/

window.onYouTubeIframeAPIReady =
    function () {

        createYouTubePlayer();

    };


/*
|--------------------------------------------------------------------------
| BUAT PLAYER YOUTUBE
|--------------------------------------------------------------------------
*/

function createYouTubePlayer()
{
    const playerElement =
        document.getElementById(
            'bpsYoutubePlayer'
        );


    if (
        !playerElement
        ||
        youtubePlayer
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | VIDEO ID
    |--------------------------------------------------------------------------
    */

    const videoId =
        playerElement.dataset.videoId;


    if (!videoId) {

        console.error(
            'Video ID YouTube tidak ditemukan.'
        );

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | PLAYER
    |--------------------------------------------------------------------------
    */

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
                        1

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
                            | VIDEO DIMUTE
                            |--------------------------------------------------------------------------
                            |
                            | Supaya:
                            |
                            | - autoplay tidak diblokir browser
                            | - suara video tidak bertabrakan
                            |   dengan suara pemanggilan antrean
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
                                event.data
                                ===
                                YT.PlayerState.ENDED
                            ) {

                                event.target.seekTo(
                                    0
                                );


                                event.target.playVideo();

                            }

                        }

                }

            }
        );
}


/*
|--------------------------------------------------------------------------
| JALANKAN YOUTUBE
|--------------------------------------------------------------------------
*/

loadYouTubeAPI();