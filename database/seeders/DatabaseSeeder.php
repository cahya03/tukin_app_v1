<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call([
            TukinSeeder::class,
            SatkerSeeder::class,
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Juru Bayar Disinfolahta',
            'email' => 'jurubayar@example.com',
            'password' => Hash::make('password'),
            'role' => 'juru_bayar',
            'kode_satker' => 'B301',
        ]);
    }
}
