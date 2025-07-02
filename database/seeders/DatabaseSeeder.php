<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Reservation;
use App\Models\RoomInfo;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat Ruangan jika belum ada
        RoomInfo::firstOrCreate(
            ['id' => 1], // Menggunakan kunci yang pasti untuk mencegah duplikat
            [
                'nama_ruangan' => 'Ruang Rapat Merdeka',
                'deskripsi' => 'Ruang rapat utama dengan fasilitas lengkap untuk presentasi dan diskusi kelompok. Terletak di lantai 2 gedung utama.',
                'kapasitas' => 25,
                'fasilitas' => 'Proyektor, Papan Tulis Digital, Sound System, AC, Meja & Kursi',
                'foto' => null, // Foto diunggah oleh admin nanti
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

        // Buat Admin contoh dari file config jika belum ada
        Admin::firstOrCreate(
            ['username' => config('admin.username')],
            [
                'password' => config('admin.password'), // akan di-hash oleh model mutator
            ]
        );
    }
}