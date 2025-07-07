<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'room_info_id', // Ditambahkan
        'dinas_id', // Ditambahkan
        'nama',
        'kontak',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'status',
        'rejection_reason',
        'checked_out_at', // Add this
    ];

    protected $casts = [
        'tanggal' => 'date',
        'checked_out_at' => 'datetime', 
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELED = 'canceled';
    const STATUS_COMPLETED = 'completed';

    public static function statusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_CANCELED => 'Dibatalkan',
            self::STATUS_COMPLETED => 'Selesai',
        ];
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke model RoomInfo
    public function roomInfo(): BelongsTo
    {
        return $this->belongsTo(RoomInfo::class);
    }
    
    // Relasi ke model Dinas
    public function dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class);
    }
    
    // Memperbarui method hasConflict untuk memeriksa berdasarkan ruangan
    public static function hasConflict($tanggal, $jam_mulai, $jam_selesai, $room_info_id, $excludeId = null)
    {
        $query = self::where('tanggal', $tanggal)
            ->where('room_info_id', $room_info_id) // Ditambahkan untuk memeriksa ruangan spesifik
            ->where('status', self::STATUS_APPROVED)
            ->where(function ($q) use ($jam_mulai, $jam_selesai) {
                $q->where(function ($q) use ($jam_mulai, $jam_selesai) {
                    $q->where('jam_mulai', '<', $jam_selesai)
                      ->where('jam_selesai', '>', $jam_mulai);
                });
            });
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
    
    public function getJamRangeAttribute()
    {
        return date('H:i', strtotime($this->jam_mulai)) . ' - ' . date('H:i', strtotime($this->jam_selesai));
    }
    
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal->format('d M Y');
    }

    public function getTanggalAttribute($value)
    {
        return $this->asDateTime($value)->setTimezone('Asia/Jakarta');
    }
}