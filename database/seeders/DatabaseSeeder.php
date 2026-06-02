<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed a teacher (guru)
        User::create([
            'name' => 'Guru Budi',
            'email' => 'guru@example.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        // Seed a student (siswa)
        User::create([
            'name' => 'Siswa Andi',
            'email' => 'siswa@example.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
        ]);
    }
}
