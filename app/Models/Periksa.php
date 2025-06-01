<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periksa extends Model
{
    protected $fillable = [
        'id_pasien',
        'id_dokter',
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
        'id_pasien' => 'required|exists:users,id,role,pasien',
        'id_dokter' => 'required|exists:users,id,role,dokter',
        'tgl_periksa' => 'required|date',
        'catatan' => 'required|string',
        'biaya_periksa' => 'required|integer|min:0',
    ];

    /**
     * Relasi ke tabel User sebagai pasien
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pasien');
    }

    /**
     * Relasi ke tabel User sebagai dokter
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_dokter');
    }

    /**
     * Relasi ke tabel DetailPeriksa
     */
    public function detailPeriksas(): HasMany
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa');
    }
}
