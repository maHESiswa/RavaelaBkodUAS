<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dokter extends Model
{
    protected $table = 'dokter';
    
    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'id_poli',
    ];

    /**
     * Validation rules for Dokter
     */
    public static $rules = [
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'no_hp' => 'required|string|max:50',
        'id_poli' => 'required|exists:poli,id',
    ];

    /**
     * Relasi ke tabel Poli
     */
    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    /**
     * Relasi ke tabel JadwalPeriksa
     */
    public function jadwalPeriksas(): HasMany
    {
        return $this->hasMany(JadwalPeriksa::class, 'id_dokter');
    }

    /**
     * Check if dokter can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->jadwalPeriksas()->count() === 0;
    }
}
