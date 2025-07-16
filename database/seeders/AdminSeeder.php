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
                'password' => Hash::make('adminganteng'), // Ganti dengan password yang aman
                'role'     => 'superadmin',
                'instansi_id' => null
            ]
        );        
    }
}