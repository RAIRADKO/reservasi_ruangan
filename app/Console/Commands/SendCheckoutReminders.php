<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\CheckoutReminderNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendCheckoutReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:send-checkout-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa reservasi yang sudah selesai tapi belum check-out dan kirim pengingat.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai memeriksa reservasi yang terlambat check out...');

        // Waktu sekarang dikurangi 15 menit
        $fifteenMinutesAgo = Carbon::now()->subMinutes(15);

        // Cari reservasi yang:
        // 1. Statusnya 'approved'
        // 2. Waktu berakhirnya (tanggal + jam_selesai) sudah lewat dari 15 menit yang lalu
        // 3. Belum melakukan check-out (checked_out_at masih null)
        $overdueReservations = Reservation::with('user', 'roomInfo')
            ->where('status', Reservation::STATUS_APPROVED)
            ->whereNull('checked_out_at')
            ->where(function ($query) use ($fifteenMinutesAgo) {
                $query->whereRaw("TIMESTAMP(tanggal, jam_selesai) <= ?", [$fifteenMinutesAgo]);
            })
            ->get();

        if ($overdueReservations->isEmpty()) {
            $this->info('Tidak ada reservasi yang perlu diingatkan saat ini.');
            return;
        }

        $this->info("Ditemukan {$overdueReservations->count()} reservasi yang terlambat check out. Mengirim email...");

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

        $this->info('Selesai mengirim semua pengingat.');
    }
}