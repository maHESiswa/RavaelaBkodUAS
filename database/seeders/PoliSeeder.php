<?php

namespace Database\Seeders;

use App\Models\Poli;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $polis = [
            [
                'nama_poli' => 'Umum',
                'keterangan' => 'Poli untuk pemeriksaan kesehatan umum dan konsultasi medis dasar'
            ],
            [
                'nama_poli' => 'Anak',
                'keterangan' => 'Poli khusus untuk pemeriksaan dan perawatan kesehatan anak-anak'
            ],
            [
                'nama_poli' => 'Kandungan',
                'keterangan' => 'Poli untuk pemeriksaan kehamilan dan kesehatan reproduksi wanita'
            ],
            [
                'nama_poli' => 'Gigi',
                'keterangan' => 'Poli untuk perawatan dan pemeriksaan kesehatan gigi dan mulut'
            ],
            [
                'nama_poli' => 'THT',
                'keterangan' => 'Poli untuk pemeriksaan telinga, hidung, dan tenggorokan'
            ],
            [
                'nama_poli' => 'Mata',
                'keterangan' => 'Poli untuk pemeriksaan dan perawatan kesehatan mata'
            ],
            [
                'nama_poli' => 'Jantung',
                'keterangan' => 'Poli untuk pemeriksaan dan perawatan penyakit jantung'
            ],
            [
                'nama_poli' => 'Kulit',
                'keterangan' => 'Poli untuk pemeriksaan dan perawatan penyakit kulit'
            ]
        ];

        foreach ($polis as $poli) {
            Poli::create($poli);
        }
    }
}
