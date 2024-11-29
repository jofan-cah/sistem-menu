<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Tambahkan user Master
        User::create([
            'name' => 'Admin Master',
            'email' => 'master@example.com',
            'password' => Hash::make('password123'), // Gunakan bcrypt atau hash
            'role' => 'Master',
        ]);

        // Tambahkan user Karyawan untuk contoh
        User::create([
            'name' => 'Karyawan User',
            'email' => 'karyawan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Karyawan',
        ]);
    }
}
