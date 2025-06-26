<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\JadwalPeriksa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalPeriksaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dokters = Dokter::all();
        
        $jadwalTemplates = [
            ['hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            ['hari' => 'Selasa', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00'],
            ['hari' => 'Rabu', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            ['hari' => 'Kamis', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00'],
            ['hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '11:00'],
        ];

        foreach ($dokters as $index => $dokter) {
            // Each doctor gets 2-3 schedules
            $scheduleCount = 2 + ($index % 2); // 2 or 3 schedules per doctor
            
            for ($i = 0; $i < $scheduleCount; $i++) {
                $template = $jadwalTemplates[$i % count($jadwalTemplates)];
                
                JadwalPeriksa::create([
                    'id_dokter' => $dokter->id,
                    'hari' => $template['hari'],
                    'jam_mulai' => $template['jam_mulai'],
                    'jam_selesai' => $template['jam_selesai'],
                ]);
                
                $this->command->info('Jadwal ' . $dokter->nama . ' - ' . $template['hari'] . ' berhasil dibuat!');
            }
        }
    }
}
