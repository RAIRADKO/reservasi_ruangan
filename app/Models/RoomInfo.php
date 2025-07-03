<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ruangan',
        'deskripsi',
        'kapasitas',
        'fasilitas',
        'foto',
    ];

    // Accessor untuk mengubah string fasilitas menjadi array
    public function getFasilitasArrayAttribute()
    {
        return array_map('trim', explode(',', $this->fasilitas));
    }
    
    // Accessor untuk mendapatkan URL foto
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        // Ganti dengan path ke gambar default jika tidak ada foto
        return asset('img/default-room.jpg'); 
    }
}