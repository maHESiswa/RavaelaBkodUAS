<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    protected $table = 'pasien';
    
    protected $fillable = [
        'nama',
        'alamat',
        'no_ktp',
        'no_hp',
        'no_rm',
    ];

    /**
     * Validation rules for Pasien
     */
    public static $rules = [
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'no_ktp' => 'required|string|size:16|unique:pasien,no_ktp',
        'no_hp' => 'required|string|max:50',
        'no_rm' => 'required|string|max:25|unique:pasien,no_rm',
    ];

    /**
     * Generate nomor rekam medis
     */
    public static function generateNoRM()
    {
        $today = date('Ymd');
        $lastPatient = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastPatient ? (int)substr($lastPatient->no_rm, -3) + 1 : 1;
        
        return 'RM-' . $today . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi ke tabel DaftarPoli
     */
    public function daftarPolis(): HasMany
    {
        return $this->hasMany(DaftarPoli::class, 'id_pasien');
    }

    /**
     * Boot method untuk auto-generate no_rm
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pasien) {
            if (empty($pasien->no_rm)) {
                $pasien->no_rm = self::generateNoRM();
            }
        });
    }
}
