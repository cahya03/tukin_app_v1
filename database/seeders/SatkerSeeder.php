<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Satker;

class SatkerSeeder extends Seeder
{
    public function run()
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

        foreach ($satkers as $satker) {
            Satker::create($satker);
        }
    }
}
