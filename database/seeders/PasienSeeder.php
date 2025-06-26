<?php

namespace Database\Seeders;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create pasien users first
        $pasienUsers = [
            [
                'nama' => 'Kenny Loggins',
                'alamat' => 'Jl. Contoh No. 123',
                'no_hp' => '081234567890',
                'email' => 'kennyloggins@gmail.com',
                'password' => Hash::make('kennyloggins@gmail.com'),
                'role' => 'pasien',
            ],
            [
                'nama' => 'Peter Parker',
                'alamat' => 'Jl. Contoh No. 456',
                'no_hp' => '081234567891',
                'email' => 'peterparker@gmail.com',
                'password' => Hash::make('peterparker@gmail.com'),
                'role' => 'pasien',
            ],
            [
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Mawar No. 15',
                'no_hp' => '081234567892',
                'email' => 'budi.santoso@gmail.com',
                'password' => Hash::make('budi.santoso@gmail.com'),
                'role' => 'pasien',
            ],
            [
                'nama' => 'Sari Dewi',
                'alamat' => 'Jl. Melati No. 22',
                'no_hp' => '081234567893',
                'email' => 'sari.dewi@gmail.com',
                'password' => Hash::make('sari.dewi@gmail.com'),
                'role' => 'pasien',
            ],
        ];

        foreach ($pasienUsers as $pasienUser) {
            $existingUser = User::where('email', $pasienUser['email'])->first();
            if (!$existingUser) {
                User::create($pasienUser);
                $this->command->info('User pasien ' . $pasienUser['nama'] . ' berhasil dibuat!');
            }
        }

        // Create pasien records
        $pasiens = [
            [
                'nama' => 'Kenny Loggins',
                'alamat' => 'Jl. Contoh No. 123',
                'no_hp' => '081234567890',
                'no_ktp' => '3201234567890001',
            ],
            [
                'nama' => 'Peter Parker',
                'alamat' => 'Jl. Contoh No. 456',
                'no_hp' => '081234567891',
                'no_ktp' => '3201234567890002',
            ],
            [
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Mawar No. 15',
                'no_hp' => '081234567892',
                'no_ktp' => '3201234567890003',
            ],
            [
                'nama' => 'Sari Dewi',
                'alamat' => 'Jl. Melati No. 22',
                'no_hp' => '081234567893',
                'no_ktp' => '3201234567890004',
            ],
        ];

        foreach ($pasiens as $pasien) {
            $existingPasien = Pasien::where('no_ktp', $pasien['no_ktp'])->first();
            if (!$existingPasien) {
                Pasien::create($pasien);
                $this->command->info('Pasien ' . $pasien['nama'] . ' berhasil dibuat!');
            }
        }
    }
}
