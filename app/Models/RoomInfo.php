<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage; // Import Storage facade
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
        'qr_code_path',
        'survey_link',
    ];

    // Menambahkan relasi ke model Reservation
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
    
    /**
     * Mendapatkan URL untuk QR code spesifik ruangan dari database.
     */
    public function getQrCodeUrlAttribute()
    {
        if ($this->qr_code_path && Storage::disk('public')->exists($this->qr_code_path)) {
            return asset('storage/' . $this->qr_code_path);
        }
        
        // Fallback ke QR code umum jika yang spesifik tidak diunggah
        return asset('img/QR Command Center.jpg');
    }
}