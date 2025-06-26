<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeriksa extends Model
{
    protected $table = 'detail_periksa';
    
    protected $fillable = [
        'id_periksa',
        'id_obat',
    ];

    /**
     * Validation rules for DetailPeriksa
     */
    public static $rules = [
        'id_periksa' => 'required|exists:periksa,id',
        'id_obat' => 'required|exists:obat,id',
    ];

    /**
     * Relasi ke tabel Periksa
     */
    public function periksa(): BelongsTo
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
    }

    /**
     * Relasi ke tabel Obat
     */
    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
