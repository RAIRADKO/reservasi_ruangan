<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RoomInfo extends Model
{
    protected $fillable = [
        'nama_ruangan',
        'deskripsi',
        'kapasitas',
        'fasilitas',
        'foto'
    ];
    
    public function getFasilitasArrayAttribute()
    {
        return explode(', ', $this->fasilitas);
    }
    
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return asset('images/default-room.jpg');
    }
}