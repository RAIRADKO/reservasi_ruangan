<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\CheckoutReminderNotification;
use App\Mail\AutoCheckoutNotification;
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
        $twelveHoursAgo = Carbon::now()->subHours(12);
        
        $overdueReservations = Reservation::with('user', 'roomInfo')
            ->where('status', Reservation::STATUS_APPROVED)
            ->whereNull('checked_out_at')
            ->where(function ($query) use ($fifteenMinutesAgo, $twelveHoursAgo) {
                $query->whereRaw("TIMESTAMP(tanggal, jam_selesai) <= ?", [$fifteenMinutesAgo])
                      ->whereRaw("TIMESTAMP(tanggal, jam_selesai) > ?", [$twelveHoursAgo]);
            })
            ->get();

        if ($overdueReservations->isEmpty()) {
            $this->info('Tidak ada reservasi yang perlu diingatkan saat ini.');
            return;
        }

        $this->info("Ditemukan {$overdueReservations->count()} reservasi yang terlambat. Mengirim email pengingat...");

        foreach ($overdueReservations as $reservation) {
            if ($reservation->user && $reservation->user->email) {
                if ($this->shouldSendReminder($reservation)) {
                    try {
                        Mail::to($reservation->user->email)->send(new CheckoutReminderNotification($reservation));
                        $this->updateReminderCount($reservation);
                        $this->info("Email pengingat dikirim ke: {$reservation->user->email} untuk reservasi #{$reservation->id}");
                    } catch (\Exception $e) {
                        $this->error("Gagal mengirim email ke {$reservation->user->email}: " . $e->getMessage());
                    }
                } else {
                    $this->info("Melewati pengingat untuk reservasi #{$reservation->id} (belum waktunya)");
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

            // Kirim email pemberitahuan auto checkout
            if ($reservation->user && $reservation->user->email) {
                try {
                    Mail::to($reservation->user->email)->send(new AutoCheckoutNotification($reservation));
                    $this->info("Email pemberitahuan auto checkout dikirim ke: {$reservation->user->email}");
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim email pemberitahuan auto checkout: " . $e->getMessage());
                }
            }

            $this->info("Reservasi #{$reservation->id} telah di-check-out secara otomatis.");
        }
    }

    private function shouldSendReminder($reservation)
    {
        $endTime = Carbon::parse($reservation->tanggal->format('Y-m-d') . ' ' . $reservation->jam_selesai);
        $now = Carbon::now();
        
        // Hitung berapa lama sudah berlalu sejak reservasi berakhir
        $minutesSinceEnd = $now->diffInMinutes($endTime);
        
        // Kirim pengingat pertama setelah 15 menit
        if ($minutesSinceEnd >= 15 && $minutesSinceEnd < 45) {
            return !$this->hasRecentReminder($reservation, 15);
        }
        
        // Kirim pengingat berikutnya setiap 30 menit
        if ($minutesSinceEnd >= 45) {
            $intervalsSince45 = floor(($minutesSinceEnd - 45) / 30);
            $expectedReminders = 1 + $intervalsSince45 + 1; // +1 for first reminder, +1 for current
            
            $currentReminders = $this->getReminderCount($reservation);
            return $currentReminders < $expectedReminders;
        }
        
        return false;
    }

    private function hasRecentReminder($reservation, $minutes)
    {
        $lastReminder = $this->getLastReminderTime($reservation);
        if (!$lastReminder) return false;
        
        return Carbon::parse($lastReminder)->diffInMinutes(Carbon::now()) < $minutes;
    }

    private function updateReminderCount($reservation)
    {
        $count = $this->getReminderCount($reservation) + 1;
        $reservation->update([
            'reminder_count' => $count,
            'last_reminder_at' => now()
        ]);
    }

    private function getReminderCount($reservation)
    {
        return $reservation->reminder_count ?? 0;
    }

    private function getLastReminderTime($reservation)
    {
        return $reservation->last_reminder_at;
    }
}