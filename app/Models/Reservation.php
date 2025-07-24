<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'batch_id',
        'user_id',
        'admin_id',
        'room_info_id',
        'dinas_id',
        'nama',
        'kontak',
        'tanggal',
        'tanggal_selesai', // Ditambahkan
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
    // Batch/grouping multi-day reservation
    public function scopeBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_selesai' => 'date', // Ditambahkan
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

    // --- LOGIKA KONFLIK DIPERBARUI ---
    public static function hasConflict($tanggal_mulai, $tanggal_selesai, $jam_mulai, $jam_selesai, $room_info_id, $excludeId = null)
    {
        $tanggal_selesai = $tanggal_selesai ?? $tanggal_mulai;

        $query = self::where('room_info_id', $room_info_id)
            ->where('status', self::STATUS_APPROVED)
            ->where(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                $q->where(function($sub) use ($tanggal_mulai, $tanggal_selesai) {
                    $sub->where('tanggal', '<=', $tanggal_selesai)
                        ->where('tanggal_selesai', '>=', $tanggal_mulai);
                })->orWhere(function($sub) use ($tanggal_mulai, $tanggal_selesai) {
                    $sub->where('tanggal', '<=', $tanggal_selesai)
                        ->whereNull('tanggal_selesai')
                        ->where('tanggal', '>=', $tanggal_mulai);
                });
            })
            ->where(function ($q) use ($jam_mulai, $jam_selesai) {
                $q->where('jam_mulai', '<', $jam_selesai)
                  ->where('jam_selesai', '>', $jam_mulai);
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
    // --- AKHIR PERUBAHAN ---

    public function getJamRangeAttribute()
    {
        return date('H:i', strtotime($this->jam_mulai)) . ' - ' . date('H:i', strtotime($this->jam_selesai));
    }

    // --- ATRIBUT BARU UNTUK TAMPILAN TANGGAL ---
    public function getTanggalFormattedAttribute()
    {
        if ($this->tanggal_selesai && $this->tanggal->ne($this->tanggal_selesai)) {
            return $this->tanggal->format('d M Y') . ' - ' . $this->tanggal_selesai->format('d M Y');
        }
        return $this->tanggal->format('d M Y');
    }
    // --- AKHIR PERUBAHAN ---

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