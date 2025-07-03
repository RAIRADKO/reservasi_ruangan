<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name', // Diubah dari 'name'
        'capacity',
        'is_available'
    ];
}