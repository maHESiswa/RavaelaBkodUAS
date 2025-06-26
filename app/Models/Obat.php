<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Obat extends Model
{
    protected $table = 'obat';
    
    protected $fillable = [
        'nama_obat',
        'kemasan',
        'harga',
    ];

    protected $casts = [
        'harga' => 'integer',
    ];

    /**
     * Validation rules for Obat
     */
    public static $rules = [
        'nama_obat' => 'required|string|max:50|unique:obat,nama_obat',
        'kemasan' => 'required|string|max:35',
        'harga' => 'required|integer|min:1000|max:500000',
    ];

    /**
     * Relasi ke tabel DetailPeriksa
     */
    public function detailPeriksas(): HasMany
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }

    /**
     * Check if obat can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->detailPeriksas()->count() === 0;
    }

    /**
     * Format harga untuk display
     */
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
