<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ==========================
// Halaman Utama (Publik)
// ==========================

Route::get('/', [RoomController::class, 'index'])->name('home')->middleware('visitor');
Route::get('/reservations/date/{date}', [RoomController::class, 'showReservationsByDate'])->name('reservations.date');
Route::get('/reservations/date/{date}/room/{room}', [RoomController::class, 'showReservationsByDateAndRoom'])->name('reservations.date.room');

// ==========================
// Autentikasi Pengguna
// ==========================

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1'); // Tambahkan ini

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==========================
// Reset Password (Untuk User)
// ==========================

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// ==========================
// Route Untuk User Login
// ==========================

Route::middleware('auth')->group(function () {
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/success', [ReservationController::class, 'success'])->name('reservations.success');
    
    // Route baru untuk detail reservasi
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');

    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    
    // Route baru untuk update password
    Route::patch('/profile/password', [UserController::class, 'updatePassword'])->name('user.password.update');

    Route::get('/my-reservations', [UserController::class, 'reservations'])->name('user.reservations');
    Route::patch('/reservations/{id}/cancel', [UserController::class, 'cancelReservation'])->name('user.reservations.cancel');
    Route::post('/reservations/check-availability', [ReservationController::class, 'checkAvailability'])->name('reservations.check-availability');

    // == PERBAIKAN DI DUA BARIS DI BAWAH INI ==
    Route::get('/reservations/{reservation}/checkout', [UserController::class, 'showCheckoutForm'])->name('user.reservations.checkout');
    Route::post('/reservations/{reservation}/complete', [UserController::class, 'completeCheckout'])->name('user.reservations.complete');
    
    Route::get('/pending', function () {
        return view('auth.pending');
    })->name('pending');
});


// ==========================
// Autentikasi Admin
// ==========================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit')->middleware('throttle:5,1'); // Tambahkan ini
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

// =================================================================
// Route Untuk Admin (Dengan Role-Based Access Control)
// =================================================================

// Grup untuk semua yang bisa diakses oleh admin dan superadmin
Route::prefix('admin')->name('admin.')->middleware(['admin', 'role:admin,superadmin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Route baru untuk membuat reservasi oleh admin
    Route::get('/reservations/create', [AdminController::class, 'showReservationForm'])->name('reservations.create');
    Route::post('/reservations', [AdminController::class, 'storeReservation'])->name('reservations.store');

    // Manajemen Reservasi
    Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations.index');
    Route::put('/reservations/{reservation}/update-status', [AdminController::class, 'updateStatus'])->name('reservations.update-status');
    Route::patch('/reservations/{reservation}/checkout', [AdminController::class, 'checkout'])->name('reservations.checkout');
    Route::delete('/reservations/{reservation}', [AdminController::class, 'destroy'])->name('reservations.destroy');

    // Manajemen Ruangan
    Route::get('rooms', [AdminController::class, 'roomIndex'])->name('room.index');
    Route::get('rooms/create', [AdminController::class, 'roomCreate'])->name('room.create');
    Route::post('rooms', [AdminController::class, 'roomStore'])->name('room.store');
    Route::get('rooms/{room}/edit', [AdminController::class, 'roomEdit'])->name('room.edit');
    Route::put('rooms/{room}', [AdminController::class, 'roomUpdate'])->name('room.update');
    Route::delete('rooms/{room}', [AdminController::class, 'roomDestroy'])->name('room.destroy');
});

// Grup khusus untuk superadmin
Route::prefix('admin')->name('admin.')->middleware(['admin', 'role:superadmin'])->group(function () {
    // Manajemen Kalender
    Route::get('/calendar-management', [AdminController::class, 'showCalendarManagement'])->name('calendar.management');
    Route::post('/blocked-dates', [AdminController::class, 'storeBlockedDate'])->name('blocked-dates.store');
    Route::delete('/blocked-dates', [AdminController::class, 'destroyBlockedDate'])->name('blocked-dates.destroy');

    // Manajemen Laporan
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
    Route::get('/reservations/export', [AdminController::class, 'exportReservations'])->name('reservations.export');

    // Manajemen Dinas
    Route::get('dinas', [AdminController::class, 'dinasIndex'])->name('dinas.index');
    Route::get('dinas/create', [AdminController::class, 'dinasCreate'])->name('dinas.create');
    Route::post('dinas', [AdminController::class, 'dinasStore'])->name('dinas.store');
    Route::get('dinas/{dina}/edit', [AdminController::class, 'dinasEdit'])->name('dinas.edit');
    Route::put('dinas/{dina}', [AdminController::class, 'dinasUpdate'])->name('dinas.update');
    Route::delete('dinas/{dina}', [AdminController::class, 'dinasDestroy'])->name('dinas.destroy');

    // Manajemen User
    Route::get('users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::get('users/create', [AdminController::class, 'usersCreate'])->name('users.create');
    Route::post('users', [AdminController::class, 'usersStore'])->name('users.store');
    Route::get('users/{user}/edit', [AdminController::class, 'usersEdit'])->name('users.edit');
    Route::put('users/{user}', [AdminController::class, 'usersUpdate'])->name('users.update');
    Route::delete('users/{user}', [AdminController::class, 'usersDestroy'])->name('users.destroy');
    Route::put('users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
    Route::put('users/{user}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');

    // Manajemen Admin
    Route::get('admins', [AdminController::class, 'adminIndex'])->name('admins.index');
    Route::get('admins/create', [AdminController::class, 'adminCreate'])->name('admins.create');
    Route::post('admins', [AdminController::class, 'adminStore'])->name('admins.store');
    Route::get('admins/{admin}/edit', [AdminController::class, 'adminEdit'])->name('admins.edit');
    Route::put('admins/{admin}', [AdminController::class, 'adminUpdate'])->name('admins.update');
    Route::delete('admins/{admin}', [AdminController::class, 'adminDestroy'])->name('admins.destroy');
});