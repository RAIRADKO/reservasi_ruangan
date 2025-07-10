<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'password' => Hash::make('adminganteng'), 
                'role'     => 'superadmin',
            ]
        );
    }
}