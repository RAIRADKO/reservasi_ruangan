<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RoomInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ruangan',
        'deskripsi',
        'kapasitas',
        'fasilitas',
        'foto',
        'instansi_id'
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function getFasilitasArrayAttribute()
    {
        return array_map('trim', explode(',', $this->fasilitas));
    }
    
    public function getFotoUrlAttribute()
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }
        return asset('img/default-room.jpg'); 
    }
}