/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
| JavaScript khusus halaman dashboard admin.
*/


// Sidebar admin
const sidebar = document.querySelector('.admin-sidebar');


// Overlay ketika sidebar dibuka di mobile
const overlay = document.getElementById('sidebarOverlay');


// Tombol hamburger dari navbar admin
const menuButton = document.getElementById('mobileMenuButton');


if (menuButton && sidebar && overlay) {

    menuButton.addEventListener('click', function () {

        sidebar.classList.toggle('mobile-open');

        overlay.style.display =
            sidebar.classList.contains('mobile-open')
                ? 'block'
                : 'none';

    });


    overlay.addEventListener('click', function () {

        sidebar.classList.remove('mobile-open');

        overlay.style.display = 'none';

    });

}