<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // <-- Pastikan baris ini ada

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        // --- Kode Diagnostik Baru ---
        $columns = Schema::getColumnListing('room_infos');
        dd($columns); // Ini akan menghentikan eksekusi dan mencetak daftar kolom
        // --- Akhir Kode Diagnostik Baru ---

        $this->call([
            RoomInfoSeeder::class,
        ]);
    }
}