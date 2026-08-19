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

function loadVoices() {

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
| VOICE SAAT HALAMAN DIBUKA
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

function updateClock() {

    const now =
        new Date();

    const timeFormatter =
        new Intl.DateTimeFormat(
            'id-ID',
            {
                timeZone: 'Asia/Makassar',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            }
        );

    const dateFormatter =
        new Intl.DateTimeFormat(
            'id-ID',
            {
                timeZone: 'Asia/Makassar',
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric',
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
            timeFormatter.format(now);

    }

    if (dateElement) {

        dateElement.textContent =
            dateFormatter.format(now);

    }

}


/*
|--------------------------------------------------------------------------
| RENDER ANTREAN AKTIF
|--------------------------------------------------------------------------
|
| Menampilkan:
|
| called  = DIPANGGIL
| serving = SEDANG DILAYANI
|
*/

function renderQueues(queues) {

    const container =
        document.getElementById(
            'queueContainer'
        );

    if (!container) {
        return;
    }

    container.replaceChildren();


    /*
    |--------------------------------------------------------------------------
    | TIDAK ADA ANTREAN AKTIF
    |--------------------------------------------------------------------------
    */

    if (queues.length === 0) {

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
                Nomor antrean akan tampil ketika
                petugas melakukan pemanggilan.
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
            | NOMOR
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
            | MASUKKAN
            |--------------------------------------------------------------------------
            */

            card.appendChild(
                number
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

function renderNextQueues(queues) {

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

    if (queues.length === 0) {

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
    | TAMPILKAN WAITING
    |--------------------------------------------------------------------------
    */

    queues.forEach(
        (queue) => {

            const item =
                document.createElement(
                    'div'
                );

            item.className =
                'next-queue-item';


            const number =
                document.createElement(
                    'strong'
                );

            number.textContent =
                queue.number;


            const service =
                document.createElement(
                    'span'
                );

            service.textContent =
                queue.service;


            item.appendChild(
                number
            );

            item.appendChild(
                service
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
| B-001
|
| menjadi:
|
| B nol nol satu
|
*/

function queueNumberToSpeech(
    queueNumber
) {

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
) {

    if (!('speechSynthesis' in window)) {
        return;
    }

    const queueText =
        queueNumberToSpeech(
            queue.number
        );

    const message =
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
        0.95;

    speech.pitch =
        1;

    speech.volume =
        1;


    /*
    |--------------------------------------------------------------------------
    | SETELAH SELESAI
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


    window.speechSynthesis.resume();

    window.speechSynthesis.speak(
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
    | RESET ID DULU
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
    | HENTIKAN SPEECH
    |--------------------------------------------------------------------------
    */

    if (
        'speechSynthesis' in window
    ) {

        window.speechSynthesis.cancel();

    }

}


/*
|--------------------------------------------------------------------------
| MULAI SUARA BERULANG
|--------------------------------------------------------------------------
*/

function startAnnouncementLoop(
    queue
) {

    /*
    |--------------------------------------------------------------------------
    | SUDAH BERJALAN UNTUK NOMOR YANG SAMA
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
    | SIMPAN ID
    |--------------------------------------------------------------------------
    */

    activeAnnouncementQueueId =
        queue.id;


    /*
    |--------------------------------------------------------------------------
    | FUNCTION PENGULANGAN
    |--------------------------------------------------------------------------
    */

    function repeatAnnouncement() {

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
                | JIKA STATUS SUDAH BERUBAH JANGAN ULANG
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
                | JEDA 4 DETIK
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
    | SUARA PERTAMA LANGSUNG
    |--------------------------------------------------------------------------
    */

    repeatAnnouncement();

}


/*
|--------------------------------------------------------------------------
| SIGNATURE DATA
|--------------------------------------------------------------------------
|
| Supaya card tidak dibuat ulang setiap polling 2 detik
| kalau datanya tidak berubah.
|
*/

function createQueueSignature(
    queues
) {

    return JSON.stringify(

        queues.map(
            (queue) => [

                queue.id,
                queue.status,
                queue.number,
                queue.service,
                queue.called_at ?? null,

            ]
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
            'queueContainer'
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


        if (!response.ok) {

            throw new Error(
                'Gagal mengambil data antrean.'
            );

        }


        const data =
            await response.json();


        if (!data.success) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN ARRAY
        |--------------------------------------------------------------------------
        */

        const queues =
            Array.isArray(
                data.queues
            )
                ? data.queues
                : [];


        const nextQueues =
            Array.isArray(
                data.next_queues
            )
                ? data.next_queues
                : [];


        /*
        |--------------------------------------------------------------------------
        | UPDATE ANTREAN AKTIF
        |--------------------------------------------------------------------------
        */

        const currentSignature =
            createQueueSignature(
                queues
            );


        if (
            currentSignature
            !== lastQueuesSignature
        ) {

            lastQueuesSignature =
                currentSignature;

            renderQueues(
                queues
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
        | SUARA HANYA UNTUK STATUS CALLED
        |--------------------------------------------------------------------------
        */

        const calledQueue =
            queues.find(
                (queue) =>
                    queue.status === 'called'
            );


        if (calledQueue) {

            startAnnouncementLoop(
                calledQueue
            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | JIKA SEMUA SUDAH SERVING / SKIPPED
            |--------------------------------------------------------------------------
            */

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
| AUTO UPDATE ANTREAN
|--------------------------------------------------------------------------
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
    | API SUDAH DIMUAT
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
    | CEK SCRIPT SUDAH ADA
    |--------------------------------------------------------------------------
    */

    if (
        document.querySelector(
            'script[src="https://www.youtube.com/iframe_api"]'
        )
    ) {

        return;

    }


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
| BUAT YOUTUBE PLAYER
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

                videoId: videoId,

                playerVars: {

                    autoplay: 1,

                    controls: 1,

                    loop: 1,

                    playlist:
                        videoId,

                    rel: 0,

                    playsinline: 1,

                },

                events: {

                    onReady:
                        function (event) {

                            /*
                            |--------------------------------------------------------------------------
                            | MUTE AGAR AUTOPLAY TIDAK DIBLOKIR
                            |--------------------------------------------------------------------------
                            */

                            event.target.mute();

                            event.target.setVolume(
                                10
                            );

                            event.target.playVideo();

                        },


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