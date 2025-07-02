<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Halaman Utama
Route::get('/', [RoomController::class, 'index'])->name('home');
Route::get('/reservations/date/{date}', [RoomController::class, 'showReservationsByDate'])
    ->name('reservations.date');

// --- Autentikasi (Satu form untuk User & Admin) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Reset Password (Hanya untuk User)
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


// --- Route untuk User yang Sudah Login ---
Route::middleware(['auth'])->group(function () {
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/success', [ReservationController::class, 'success'])->name('reservations.success');
    
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/my-reservations', [UserController::class, 'reservations'])->name('user.reservations');
    Route::patch('/reservations/{id}/cancel', [UserController::class, 'cancelReservation'])->name('user.reservations.cancel');
});


// --- Admin Routes ---
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations');
    // Menggunakan PUT untuk update status
    Route::put('/reservations/{reservation}/update-status', [AdminController::class, 'updateStatus'])->name('reservations.update-status');
    Route::delete('/reservations/{reservation}', [AdminController::class, 'destroy'])->name('reservations.destroy');
    Route::get('/room/edit', [AdminController::class, 'editRoom'])->name('room.edit');
    // Menggunakan PUT untuk update ruangan
    Route::put('/room/update', [AdminController::class, 'updateRoom'])->name('room.update');
});