<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Undangan
Route::get('/undangan/{code}', [
    InvitationController::class,
    'show'
])->name('invitation.show');


// RSVP
Route::post('/undangan/{code}/rsvp', [
    RsvpController::class,
    'store'
])->name('rsvp.store');


// Ucapan
Route::post('/undangan/{code}/wish', [
    WishController::class,
    'store'
])->name('wish.store');


// Load ucapan
Route::get('/undangan/{code}/wishes', [
    InvitationController::class,
    'wishes'
])->name('wishes.list');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [
    AuthController::class,
    'loginForm'
])->name('login');

Route::post('/login', [
    AuthController::class,
    'login'
])->name('login.process');

Route::post('/logout', [
    AuthController::class,
    'logout'
])->name('logout');


/*
|--------------------------------------------------------------------------
| STAFF ROUTES
| Admin + Petugas
|--------------------------------------------------------------------------
*/

Route::middleware(['staff'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [
        DashboardController::class,
        'index'
    ])->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Scanner QR
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/checkin', [
        CheckinController::class,
        'scanner'
    ])->name('admin.checkin.scanner');

    Route::post('/admin/checkin/find', [
        CheckinController::class,
        'find'
    ])->name('admin.checkin.find');


    /*
    |--------------------------------------------------------------------------
    | Check-in
    |--------------------------------------------------------------------------
    */

    Route::post('/admin/checkin/{guest}/confirm', [
        CheckinController::class,
        'confirm'
    ])->name('admin.checkin.confirm');


    /*
    |--------------------------------------------------------------------------
    | Check-in Manual
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/checkin/manual', [
        CheckinController::class,
        'manual'
    ])->name('admin.checkin.manual');

    Route::get('/admin/checkin/manual/search', [
        CheckinController::class,
        'searchManual'
    ])->name('admin.checkin.manual.search');

});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
| Khusus role admin
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Daftar Tamu
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/tamu', [
        GuestController::class,
        'index'
    ])->name('admin.guests.index');


    /*
    |--------------------------------------------------------------------------
    | Import Excel
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/import-tamu', [
        GuestController::class,
        'importForm'
    ])->name('admin.guests.import.form');

    Route::post('/admin/guests/import', [
        GuestController::class,
        'import'
    ])->name('admin.guests.import');


    /*
    |--------------------------------------------------------------------------
    | Template & Export Excel
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/tamu/template', [
        GuestController::class,
        'template'
    ])->name('admin.guests.template');

    Route::get('/admin/tamu/export', [
        GuestController::class,
        'export'
    ])->name('admin.guests.export');


    /*
    |--------------------------------------------------------------------------
    | Edit / Delete Tamu
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/tamu/{guest}/edit', [
        GuestController::class,
        'edit'
    ])->name('admin.guests.edit');

    Route::put('/admin/tamu/{guest}', [
        GuestController::class,
        'update'
    ])->name('admin.guests.update');

    Route::delete('/admin/tamu/{guest}', [
        GuestController::class,
        'destroy'
    ])->name('admin.guests.destroy');


    /*
    |--------------------------------------------------------------------------
    | QR Tamu
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/tamu/{guest}/qr', [
        QrCodeController::class,
        'show'
    ])->name('admin.guests.qr');

    Route::get('/admin/tamu/{guest}/qr/download', [
        QrCodeController::class,
        'download'
    ])->name('admin.guests.qr.download');


    /*
    |--------------------------------------------------------------------------
    | Kelola User / Petugas
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/users', [
        UserController::class,
        'index'
    ])->name('admin.users.index');

    Route::get('/admin/users/create', [
        UserController::class,
        'create'
    ])->name('admin.users.create');

    Route::post('/admin/users', [
        UserController::class,
        'store'
    ])->name('admin.users.store');

    Route::get('/admin/users/{user}/edit', [
        UserController::class,
        'edit'
    ])->name('admin.users.edit');

    Route::put('/admin/users/{user}', [
        UserController::class,
        'update'
    ])->name('admin.users.update');

    Route::delete('/admin/users/{user}', [
        UserController::class,
        'destroy'
    ])->name('admin.users.destroy');

});