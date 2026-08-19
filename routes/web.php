<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetugasQueueController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\ScheduleController;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK / PENGUNJUNG
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
});


Route::get(
    '/display',
    [DisplayController::class, 'index']
)->name('display');


Route::get(
    '/layanan',
    [QueueController::class, 'layanan']
)->name('layanan');


Route::post(
    '/ambil-antrian',
    [QueueController::class, 'ambilAntrian']
)->name('ambil.antrian');


Route::get(
    '/status-antrian/{public_token}',
    [QueueController::class, 'status']
)->name('status.antrian');


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('login');
})->name('login');


Route::post(
    '/login',
    [AuthController::class, 'login']
)->name('login.process');


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN UTAMA
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin_utama'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        function () {
            return view('admin.dashboard');
        }
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | ANTREAN ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/antrean',
        [QueueController::class, 'adminIndex']
    )->name('admin.antrean');

    Route::delete(
        '/admin/antrean/{id}',
        [QueueController::class, 'destroyAdmin']
    )->name('admin.antrean.destroy');

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN PENGGUNA
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/pengguna',
        [UserController::class, 'index']
    )->name('admin.users');


    Route::post(
        '/admin/pengguna',
        [UserController::class, 'store']
    )->name('admin.users.store');


    /*
    |--------------------------------------------------------------------------
    | JADWAL PETUGAS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/jadwal',
        [ScheduleController::class, 'index']
    )->name('admin.jadwal');


    Route::post(
        '/admin/jadwal',
        [ScheduleController::class, 'store']
    )->name('admin.jadwal.store');


    Route::get(
        '/admin/jadwal/{id}/edit',
        [ScheduleController::class, 'edit']
    )->name('admin.jadwal.edit');


    Route::put(
        '/admin/jadwal/{id}',
        [ScheduleController::class, 'update']
    )->name('admin.jadwal.update');


    Route::delete(
        '/admin/jadwal/{id}',
        [ScheduleController::class, 'destroy']
    )->name('admin.jadwal.destroy');
});


/*
|--------------------------------------------------------------------------
| PETUGAS
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:petugas'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PETUGAS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/petugas/dashboard',
        [PetugasQueueController::class, 'dashboard']
    )->name('petugas.dashboard');


    /*
    |--------------------------------------------------------------------------
    | ANTREAN PETUGAS
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/petugas/antrean/panggil',
        [PetugasQueueController::class, 'panggil']
    )->name('petugas.antrean.panggil');


    Route::post(
        '/petugas/antrean/{id}/panggil-ulang',
        [PetugasQueueController::class, 'panggilUlang']
    )->name('petugas.antrean.panggil-ulang');


    Route::post(
        '/petugas/antrean/mulai',
        [PetugasQueueController::class, 'mulai']
    )->name('petugas.antrean.mulai');


    Route::post(
        '/petugas/antrean/lewati',
        [PetugasQueueController::class, 'lewati']
    )->name('petugas.antrean.lewati');


    Route::post(
        '/petugas/antrean/selesai',
        [PetugasQueueController::class, 'selesai']
    )->name('petugas.antrean.selesai');
});
