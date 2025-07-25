<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satkers = [
            ['kode_satker' => 'B301', 'nama_satker' => 'Disinfolahtaau'],
            ['kode_satker' => 'B302', 'nama_satker' => 'Disku'],
            ['kode_satker' => 'B303', 'nama_satker' => 'Setum'],
            ['kode_satker' => 'B304', 'nama_satker' => 'Diswatpers'],
            ['kode_satker' => 'B305', 'nama_satker' => 'Disdik'],
            ['kode_satker' => 'B306', 'nama_satker' => 'Sintel'],
            ['kode_satker' => 'B307', 'nama_satker' => 'Diskes'],
            ['kode_satker' => 'B308', 'nama_satker' => 'Spersau'],
            ['kode_satker' => 'B309', 'nama_satker' => 'Disminpers'],
        ];

        $users = [];
        
        foreach ($satkers as $satker) {
            $users[] = [
                'name' => 'Admin ' . $satker['nama_satker'],
                'email' => strtolower($satker['kode_satker']) . '@tukin.mil.id',
                'kode_satker' => $satker['kode_satker'],
                'password' => Hash::make('password123'),
                'role' => 'juru_bayar',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}