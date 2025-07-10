<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\CheckoutReminderNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendCheckoutReminders extends Command
{
    protected $signature = 'reservations:send-checkout-reminders';
    protected $description = 'Periksa reservasi yang sudah selesai tapi belum check-out, kirim pengingat, dan lakukan check-out otomatis.';

    public function handle()
    {
        $this->info('Mulai memeriksa reservasi...');
        $this->handleAutomaticCheckouts();
        $this->handleCheckoutReminders();
        $this->info('Pemeriksaan reservasi selesai.');
    }

    private function handleCheckoutReminders()
    {
        $this->info('Memeriksa reservasi yang terlambat check out untuk pengingat...');
        $fifteenMinutesAgo = Carbon::now()->subMinutes(15);
        $twentyFourHoursAgo = Carbon::now()->subHours(12);
        $overdueReservations = Reservation::with('user', 'roomInfo')
            ->where('status', Reservation::STATUS_APPROVED)
            ->whereNull('checked_out_at')
            ->where(function ($query) use ($fifteenMinutesAgo, $twentyFourHoursAgo) {
                $query->whereRaw("TIMESTAMP(tanggal, jam_selesai) <= ?", [$fifteenMinutesAgo])
                      ->whereRaw("TIMESTAMP(tanggal, jam_selesai) > ?", [$twentyFourHoursAgo]);
            })
            ->get();

        if ($overdueReservations->isEmpty()) {
            $this->info('Tidak ada reservasi yang perlu diingatkan saat ini.');
            return;
        }

        $this->info("Ditemukan {$overdueReservations->count()} reservasi yang terlambat. Mengirim email pengingat...");

        foreach ($overdueReservations as $reservation) {
            if ($reservation->user && $reservation->user->email) {
                try {
                    Mail::to($reservation->user->email)->send(new CheckoutReminderNotification($reservation));
                    $this->info("Email pengingat dikirim ke: {$reservation->user->email} untuk reservasi #{$reservation->id}");
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim email ke {$reservation->user->email}: " . $e->getMessage());
                }
            }
        }
    }

    private function handleAutomaticCheckouts()
    {
        $this->info('Memeriksa reservasi untuk check-out otomatis...');
        $twelveHoursAgo = Carbon::now()->subHours(12);
        $autoCheckoutReservations = Reservation::with('user', 'roomInfo')
            ->where('status', Reservation::STATUS_APPROVED)
            ->whereNull('checked_out_at')
            ->where(function ($query) use ($twelveHoursAgo) {
                $query->whereRaw("TIMESTAMP(tanggal, jam_selesai) <= ?", [$twelveHoursAgo]);
            })
            ->get();

        if ($autoCheckoutReservations->isEmpty()) {
            $this->info('Tidak ada reservasi yang perlu di-check-out otomatis.');
            return;
        }

        $this->info("Ditemukan {$autoCheckoutReservations->count()} reservasi yang akan di-check-out otomatis.");
        foreach ($autoCheckoutReservations as $reservation) {
            $reservation->update([
                'status' => Reservation::STATUS_COMPLETED,
                'checked_out_at' => now(),
                'satisfaction_rating' => 5,
                'feedback' => 'Check-out otomatis oleh sistem setelah 12 jam.'
            ]);
            $this->info("Reservasi #{$reservation->id} telah di-check-out secara otomatis.");
        }
    }
}