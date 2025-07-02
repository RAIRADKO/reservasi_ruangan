<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'kontak',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELED = 'canceled';

    public static function statusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_CANCELED => 'Dibatalkan',
        ];
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public static function hasConflict($tanggal, $jam_mulai, $jam_selesai, $excludeId = null)
    {
        $query = self::where('tanggal', $tanggal)
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
}