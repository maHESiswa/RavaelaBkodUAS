<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create dokter users first
        $dokterUsers = [
            [
                'nama' => 'Dr. John Doe',
                'alamat' => 'Jl. Contoh No. 123',
                'no_hp' => '081234567890',
                'email' => 'johndoe@gmail.com',
                'password' => Hash::make('johndoe@gmail.com'),
                'role' => 'dokter',
            ],
            [
                'nama' => 'Dr. Jane Smith',
                'alamat' => 'Jl. Contoh No. 456',
                'no_hp' => '081234567891',
                'email' => 'janesmith@gmail.com',
                'password' => Hash::make('janesmith@gmail.com'),
                'role' => 'dokter',
            ],
            [
                'nama' => 'Dr. Ahmad Wijaya',
                'alamat' => 'Jl. Merdeka No. 789',
                'no_hp' => '081234567892',
                'email' => 'ahmad.wijaya@gmail.com',
                'password' => Hash::make('ahmad.wijaya@gmail.com'),
                'role' => 'dokter',
            ],
            [
                'nama' => 'Dr. Siti Nurhaliza',
                'alamat' => 'Jl. Sudirman No. 321',
                'no_hp' => '081234567893',
                'email' => 'siti.nurhaliza@gmail.com',
                'password' => Hash::make('siti.nurhaliza@gmail.com'),
                'role' => 'dokter',
            ],
        ];

        foreach ($dokterUsers as $dokterUser) {
            $existingUser = User::where('email', $dokterUser['email'])->first();
            if (!$existingUser) {
                User::create($dokterUser);
                $this->command->info('User dokter ' . $dokterUser['nama'] . ' berhasil dibuat!');
            }
        }

        // Create dokter records
        $dokters = [
            [
                'nama' => 'Dr. John Doe',
                'alamat' => 'Jl. Contoh No. 123',
                'no_hp' => '081234567890',
                'id_poli' => Poli::where('nama_poli', 'Umum')->first()->id,
            ],
            [
                'nama' => 'Dr. Jane Smith',
                'alamat' => 'Jl. Contoh No. 456',
                'no_hp' => '081234567891',
                'id_poli' => Poli::where('nama_poli', 'Anak')->first()->id,
            ],
            [
                'nama' => 'Dr. Ahmad Wijaya',
                'alamat' => 'Jl. Merdeka No. 789',
                'no_hp' => '081234567892',
                'id_poli' => Poli::where('nama_poli', 'Jantung')->first()->id,
            ],
            [
                'nama' => 'Dr. Siti Nurhaliza',
                'alamat' => 'Jl. Sudirman No. 321',
                'no_hp' => '081234567893',
                'id_poli' => Poli::where('nama_poli', 'Kandungan')->first()->id,
            ],
        ];

        foreach ($dokters as $dokter) {
            $existingDokter = Dokter::where('nama', $dokter['nama'])->first();
            if (!$existingDokter) {
                Dokter::create($dokter);
                $this->command->info('Dokter ' . $dokter['nama'] . ' berhasil dibuat!');
            }
        }
    }
}
