<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Reservation;
use App\Models\RoomInfo;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat Ruangan jika belum ada
        RoomInfo::firstOrCreate(
            [],
            [
                'nama_ruangan' => 'Ruang Rapat Merdeka',
                'deskripsi' => 'Ruang rapat utama dengan fasilitas lengkap untuk presentasi dan diskusi kelompok. Terletak di lantai 2 gedung utama.',
                'kapasitas' => 25,
                'fasilitas' => 'Proyektor, Papan Tulis Digital, Sound System, AC, Meja & Kursi',
                'foto' => null, // Foto bisa diupload oleh admin nanti
            ]
        );

        // Buat User contoh jika belum ada
        $user = User::firstOrCreate(
            ['nip' => '123456789012345678'],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => 'password', // akan di-hash oleh model mutator
            ]
        );

        // Buat Admin contoh jika belum ada
        Admin::firstOrCreate(
            ['username' => config('admin.username', 'admin')],
            [
                'password' => config('admin.password', 'password'), // akan di-hash oleh model mutator
            ]
        );

        // Buat beberapa reservasi contoh
        Reservation::firstOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => now()->addDays(2)->toDateString(),
                'jam_mulai' => '09:00',
            ],
            [
                'nama' => $user->name,
                'kontak' => '081234567890',
                'jam_selesai' => '11:00',
                'keperluan' => 'Rapat evaluasi kuartal tim marketing.',
                'status' => 'approved',
            ]
        );

        Reservation::firstOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => now()->addDays(3)->toDateString(),
                'jam_mulai' => '13:00',
            ],
            [
                'nama' => $user->name,
                'kontak' => '081234567891',
                'jam_selesai' => '15:00',
                'keperluan' => 'Presentasi proyek baru kepada stakeholder.',
                'status' => 'pending',
            ]
        );
    }
}