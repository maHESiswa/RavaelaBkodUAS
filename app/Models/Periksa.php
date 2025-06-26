<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periksa extends Model
{
    protected $table = 'periksa';
    
    protected $fillable = [
        'id_daftar_poli',
        'tgl_periksa',
        'catatan',
        'biaya_periksa',
    ];

    protected $casts = [
        'tgl_periksa' => 'datetime',
        'biaya_periksa' => 'integer',
    ];

    /**
     * Validation rules for Periksa
     */
    public static $rules = [
        'id_daftar_poli' => 'required|exists:daftar_poli,id',
        'tgl_periksa' => 'required|date',
        'catatan' => 'nullable|string|max:1000',
        'biaya_periksa' => 'required|integer|min:10000|max:1000000',
    ];

    /**
     * Relasi ke tabel DaftarPoli
     */
    public function daftarPoli(): BelongsTo
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli');
    }

    /**
     * Relasi ke tabel DetailPeriksa
     */
    public function detailPeriksas(): HasMany
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa');
    }

    /**
     * Get pasien through daftar_poli
     */
    public function getPasienAttribute()
    {
        return $this->daftarPoli->pasien ?? null;
    }

    /**
     * Get dokter through daftar_poli and jadwal
     */
    public function getDokterAttribute()
    {
        return $this->daftarPoli->jadwalPeriksa->dokter ?? null;
    }

    /**
     * Calculate total biaya including obat
     */
    public function getTotalBiayaAttribute()
    {
        $biayaObat = $this->detailPeriksas->sum(function ($detail) {
            return $detail->obat->harga ?? 0;
        });
        
        return $this->biaya_periksa + $biayaObat;
    }
}
