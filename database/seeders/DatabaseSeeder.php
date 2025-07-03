<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Baris ini masih bisa digunakan untuk membuat data user dummy
        User::factory(10)->create();

        // Hapus kode diagnostik dari sini
        // $columns = Schema::getColumnListing('room_infos');
        // dd($columns); 
        // Sampai sini

        // Panggil seeder lain yang dibutuhkan
        $this->call([
            RoomInfoSeeder::class,
        ]);
    }
}