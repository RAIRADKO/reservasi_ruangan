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
            'nama_ruangan' => 'Ruang Rapat Oemar Seno Adji',
            'deskripsi' => 'Ruang rapat utama yang terletak di lantai 2 dengan fasilitas modern untuk menunjang kebutuhan rapat Anda.',
            'kapasitas' => 25,
            'fasilitas' => 'Proyektor, Papan Tulis, Sound System, AC, Meja Rapat, Kursi',
            'foto' => null, // Biarkan null atau isi dengan path jika ada gambar default
        ]);
    }
}