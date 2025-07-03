<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['username' => config('admin.username')],
            [
                'username' => config('admin.username'),
                'password' => Hash::make(config('admin.password'))
            ]
        );
    }
}