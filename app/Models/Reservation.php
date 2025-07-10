<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'room_info_id',
        'dinas_id',
        'nama',
        'kontak',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'fasilitas_terpilih',
        'status',
        'rejection_reason',
        'checked_out_at',
        'satisfaction_rating', 
        'feedback', 
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

    public function roomInfo(): BelongsTo
    {
        return $this->belongsTo(RoomInfo::class);
    }
    
    public function dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class);
    }
    
    public static function hasConflict($tanggal, $jam_mulai, $jam_selesai, $room_info_id, $excludeId = null)
    {
        $query = self::where('tanggal', $tanggal)
            ->where('room_info_id', $room_info_id)
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

    public function getFasilitasTerpilihArrayAttribute()
    {
        if ($this->fasilitas_terpilih) {
            return explode(',', $this->fasilitas_terpilih);
        }
        return [];
    }
}