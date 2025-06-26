<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DaftarPoli extends Model
{
    protected $table = 'daftar_poli';
    
    protected $fillable = [
        'id_pasien',
        'id_jadwal',
        'keluhan',
        'no_antrian',
    ];

    /**
     * Validation rules for DaftarPoli
     */
    public static $rules = [
        'id_pasien' => 'required|exists:pasien,id',
        'id_jadwal' => 'required|exists:jadwal_periksa,id',
        'keluhan' => 'required|string|min:10|max:500',
    ];

    /**
     * Generate nomor antrian
     */
    public static function generateNoAntrian($id_jadwal)
    {
        $today = today();
        $lastAntrian = self::where('id_jadwal', $id_jadwal)
            ->whereDate('created_at', $today)
            ->orderBy('no_antrian', 'desc')
            ->first();
        
        return $lastAntrian ? $lastAntrian->no_antrian + 1 : 1;
    }

    /**
     * Relasi ke tabel Pasien
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    /**
     * Relasi ke tabel JadwalPeriksa
     */
    public function jadwalPeriksa(): BelongsTo
    {
        return $this->belongsTo(JadwalPeriksa::class, 'id_jadwal');
    }

    /**
     * Relasi ke tabel Periksa
     */
    public function periksa(): HasOne
    {
        return $this->hasOne(Periksa::class, 'id_daftar_poli');
    }

    /**
     * Boot method untuk auto-generate no_antrian
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($daftarPoli) {
            if (empty($daftarPoli->no_antrian)) {
                $daftarPoli->no_antrian = self::generateNoAntrian($daftarPoli->id_jadwal);
            }
        });
    }

    /**
     * Get status pemeriksaan
     */
    public function getStatusAttribute()
    {
        if ($this->periksa) {
            return 'Selesai';
        }
        
        // Logic untuk menentukan status berdasarkan antrian
        $currentAntrian = self::where('id_jadwal', $this->id_jadwal)
            ->whereDate('created_at', today())
            ->whereHas('periksa')
            ->count();
            
        if ($this->no_antrian <= $currentAntrian + 1) {
            return 'Sedang Diperiksa';
        }
        
        return 'Menunggu';
    }
}
