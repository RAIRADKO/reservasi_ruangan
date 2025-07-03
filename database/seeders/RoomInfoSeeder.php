<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomInfo;

class RoomInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoomInfo::create([
            'room_name' => 'Ruang Rapat 3', // Diubah dari 'name'
            'capacity' => 10,
            'is_available' => true,
        ]);
    }
}