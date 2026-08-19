/*
|--------------------------------------------------------------------------
| DISPLAY ANTREAN
|--------------------------------------------------------------------------
| JavaScript khusus halaman monitor antrean.
*/


/*
|--------------------------------------------------------------------------
| JAM WITA REAL-TIME
|--------------------------------------------------------------------------
*/

function updateClock() {

    const now = new Date();


    // Waktu Makassar / WITA
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


    // Tanggal Makassar / WITA
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
| JALANKAN JAM
|--------------------------------------------------------------------------
*/

updateClock();

setInterval(
    updateClock,
    1000
);