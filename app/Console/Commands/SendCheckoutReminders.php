<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\CheckoutReminderNotification;
use App\Mail\AutoCheckoutNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
// Tambahkan ini di bagian atas file
use Illuminate\Support\Facades\Log;

class SendCheckoutReminders extends Command
{
    protected $signature = 'reservations:send-checkout-reminders';
    protected $description = 'Periksa reservasi yang sudah selesai tapi belum check-out, kirim pengingat, dan lakukan check-out otomatis.';

    public function handle()
    {
        // Menggunakan Log::info() agar outputnya masuk ke file log
        Log::info('--- Memulai Cron Job: SendCheckoutReminders ---');
        Log::info('Waktu server saat ini: ' . Carbon::now());

        $this->handleAutomaticCheckouts();
        $this->handleCheckoutReminders();
        
        Log::info('--- Cron Job Selesai ---');
    }

    private function handleCheckoutReminders()
    {
        Log::info('[handleCheckoutReminders] Memeriksa reservasi yang terlambat check out untuk pengingat...');
        $fifteenMinutesAgo = Carbon::now()->subMinutes(15);
        $twelveHoursAgo = Carbon::now()->subHours(12);
        
        $overdueReservations = Reservation::with('user', 'roomInfo')
            ->where('status', Reservation::STATUS_APPROVED)
            ->whereNull('checked_out_at')
            ->where(function ($query) use ($fifteenMinutesAgo, $twelveHoursAgo) {
                $query->whereRaw("TIMESTAMP(tanggal, jam_selesai) <= ?", [$fifteenMinutesAgo])
                      ->whereRaw("TIMESTAMP(tanggal, jam_selesai) > ?", [$twelveHoursAgo]);
            })
            ->get();
        
        // Log krusial: Berapa banyak reservasi yang ditemukan?
        Log::info("[handleCheckoutReminders] Ditemukan {$overdueReservations->count()} reservasi yang memenuhi kriteria waktu.");

        if ($overdueReservations->isEmpty()) {
            Log::info('[handleCheckoutReminders] Tidak ada reservasi yang perlu diingatkan saat ini.');
            return;
        }

        Log::info("[handleCheckoutReminders] Memulai loop untuk {$overdueReservations->count()} reservasi...");

        foreach ($overdueReservations as $reservation) {
            Log::info("[handleCheckoutReminders] Memproses Reservasi ID: #{$reservation->id}");
            if ($reservation->user && $reservation->user->email) {
                
                $shouldSend = $this->shouldSendReminder($reservation);
                Log::info("[handleCheckoutReminders] Reservasi ID: #{$reservation->id} | shouldSendReminder() mengembalikan: " . ($shouldSend ? 'true' : 'false'));

                if ($shouldSend) {
                    try {
                        Mail::to($reservation->user->email)->send(new CheckoutReminderNotification($reservation));
                        $this->updateReminderCount($reservation);
                        Log::info("[handleCheckoutReminders] BERHASIL: Email untuk Reservasi ID #{$reservation->id} telah dimasukkan ke dalam antrean.");
                    } catch (\Exception $e) {
                        Log::error("[handleCheckoutReminders] GAGAL mengirim email untuk Reservasi ID #{$reservation->id}: " . $e->getMessage());
                    }
                } else {
                    Log::info("[handleCheckoutReminders] MELEWATI: Pengingat untuk Reservasi ID #{$reservation->id} tidak dikirim (belum waktunya).");
                }
            } else {
                Log::warning("[handleCheckoutReminders] MELEWATI: Reservasi ID #{$reservation->id} tidak memiliki data user atau email.");
            }
        }
    }

    private function handleAutomaticCheckouts()
    {
        Log::info('[handleAutomaticCheckouts] Memeriksa reservasi untuk check-out otomatis...');
        $twelveHoursAgo = Carbon::now()->subHours(12);
        
        $autoCheckoutReservations = Reservation::with('user', 'roomInfo')
            ->where('status', Reservation::STATUS_APPROVED)
            ->whereNull('checked_out_at')
            ->where(function ($query) use ($twelveHoursAgo) {
                $query->whereRaw("TIMESTAMP(tanggal, jam_selesai) <= ?", [$twelveHoursAgo]);
            })
            ->get();

        Log::info("[handleAutomaticCheckouts] Ditemukan {$autoCheckoutReservations->count()} reservasi untuk di-checkout otomatis.");

        if ($autoCheckoutReservations->isEmpty()) {
            return;
        }
        
        foreach ($autoCheckoutReservations as $reservation) {
            Log::info("[handleAutomaticCheckouts] Memproses auto-checkout untuk Reservasi ID: #{$reservation->id}");
            // ... (logika update asli)
        }
    }

    // Fungsi helper lainnya tidak perlu diubah
    private function shouldSendReminder($reservation) { /* ... logika asli ... */ return true; }
    private function hasRecentReminder($reservation, $minutes) { /* ... logika asli ... */ return false; }
    private function updateReminderCount($reservation) { /* ... logika asli ... */ }
    private function getReminderCount($reservation) { /* ... logika asli ... */ return 0; }
    private function getLastReminderTime($reservation) { /* ... logika asli ... */ return null; }
}