<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; 

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan command setiap 15 menit untuk memeriksa reminder dan auto checkout
Schedule::command('reservations:send-checkout-reminders')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->runInBackground();

// Opsional: Jalankan command khusus untuk auto checkout setiap jam
// Schedule::command('reservations:auto-checkout')->hourly();