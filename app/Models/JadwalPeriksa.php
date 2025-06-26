<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalPeriksa extends Model
{
    protected $table = 'jadwal_periksa';
    
    protected $fillable = [
        'id_dokter',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    /**
     * Validation rules for JadwalPeriksa
     */
    public static $rules = [
        'id_dokter' => 'required|exists:dokter,id',
        'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    ];

    /**
     * Relasi ke tabel Dokter
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    /**
     * Relasi ke tabel DaftarPoli
     */
    public function daftarPolis(): HasMany
    {
        return $this->hasMany(DaftarPoli::class, 'id_jadwal');
    }

    /**
     * Check if jadwal can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->daftarPolis()->count() === 0;
    }

    /**
     * Check for schedule overlap
     */
    public static function hasOverlap($id_dokter, $hari, $jam_mulai, $jam_selesai, $excludeId = null)
    {
        $query = self::where('id_dokter', $id_dokter)
            ->where('hari', $hari)
            ->where(function ($q) use ($jam_mulai, $jam_selesai) {
                $q->whereBetween('jam_mulai', [$jam_mulai, $jam_selesai])
                  ->orWhereBetween('jam_selesai', [$jam_mulai, $jam_selesai])
                  ->orWhere(function ($q2) use ($jam_mulai, $jam_selesai) {
                      $q2->where('jam_mulai', '<=', $jam_mulai)
                         ->where('jam_selesai', '>=', $jam_selesai);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
