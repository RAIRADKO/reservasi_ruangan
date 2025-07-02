<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Kredensial Admin Default
    |--------------------------------------------------------------------------
    |
    | Nilai ini digunakan oleh DatabaseSeeder untuk membuat akun admin
    | pertama kali. Sebaiknya ganti nilai default ini di file .env Anda.
    |
    */
    'username' => env('ADMIN_USERNAME', 'admin'),
    'password' => env('ADMIN_PASSWORD', 'password'),
];