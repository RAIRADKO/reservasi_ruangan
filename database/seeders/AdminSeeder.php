<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Dinas;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Superadmin
        Admin::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'email'    => 'superadmin@example.com',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'role'     => 'superadmin',
                'instansi_id' => null
            ]
        );

        // Mengambil 3 instansi pertama untuk admin
        $dinas = Dinas::take(3)->get();

        if ($dinas->count() >= 3) {
            // Membuat Admin untuk Dinas Komunikasi, Informatika, Statistik dan Persandian
            Admin::updateOrCreate(
                ['username' => 'admin_kominfo'],
                [
                    'email'    => 'admin.kominfo@example.com',
                    'password' => Hash::make('password'),
                    'role'     => 'admin',
                    'instansi_id' => $dinas[0]->id,
                ]
            );

            // Membuat Admin untuk Sekretariat Daerah
            Admin::updateOrCreate(
                ['username' => 'admin_setda'],
                [
                    'email'    => 'admin.setda@example.com',
                    'password' => Hash::make('password'),
                    'role'     => 'admin',
                    'instansi_id' => $dinas[1]->id,
                ]
            );

            // Membuat Admin untuk Dinas Pendidikan dan Kebudayaan
            Admin::updateOrCreate(
                ['username' => 'admin_dindikbud'],
                [
                    'email'    => 'admin.dindikbud@example.com',
                    'password' => Hash::make('password'),
                    'role'     => 'admin',
                    'instansi_id' => $dinas[2]->id,
                ]
            );
        }
    }
}