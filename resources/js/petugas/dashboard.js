/*
|--------------------------------------------------------------------------
| DASHBOARD PETUGAS
|--------------------------------------------------------------------------
| JavaScript khusus halaman dashboard petugas.
*/


function updateDateTime() {

    const now = new Date();


    const date = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });


    const time = now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });


    const currentDate = document.getElementById('currentDate');
    const currentTime = document.getElementById('currentTime');


    if (currentDate) {
        currentDate.textContent = date;
    }


    if (currentTime) {
        currentTime.textContent = time;
    }

}


updateDateTime();


setInterval(
    updateDateTime,
    1000
);