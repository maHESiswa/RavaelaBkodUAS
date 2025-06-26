<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = [
            'nama' => 'Administrator',
            'alamat' => 'Jl. Admin No. 1',
            'no_hp' => '081234567899',
            'email' => 'admin@sismenkes.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ];

        $existingAdmin = User::where('email', $admin['email'])->first();
        if (!$existingAdmin) {
            User::create($admin);
            $this->command->info('Admin user berhasil dibuat!');
            $this->command->info('Email: admin@sismenkes.com');
            $this->command->info('Password: admin123');
        } else {
            $this->command->info('Admin user sudah ada.');
        }
    }
}
