<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pengaturan Ruangan
    |--------------------------------------------------------------------------
    |
    | File ini berisi konfigurasi khusus untuk fungsionalitas ruangan,
    | seperti jam operasional.
    |
    */
    'operating_hours' => [
        'start' => env('ROOM_OPERATING_START', '08:00'),
        'end' => env('ROOM_OPERATING_END', '16:00'),
    ],
];