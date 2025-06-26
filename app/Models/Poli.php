<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poli extends Model
{
    protected $table = 'poli';
    
    protected $fillable = [
        'nama_poli',
        'keterangan',
    ];

    /**
     * Validation rules for Poli
     */
    public static $rules = [
        'nama_poli' => 'required|string|max:25|unique:poli,nama_poli',
        'keterangan' => 'nullable|string',
    ];

    /**
     * Relasi ke tabel Dokter
     */
    public function dokters(): HasMany
    {
        return $this->hasMany(Dokter::class, 'id_poli');
    }

    /**
     * Check if poli can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->dokters()->count() === 0;
    }
}
