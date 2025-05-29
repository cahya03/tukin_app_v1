<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TukinSeeder extends Seeder

{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tukin')->insert([
            'id_tukin' => '1',
            'id_proses' => '1',
            'tni_pns' => 'TNI',
            'nomor_tukin' => 'TUKIN-001',
            'kdsatker' => '12345678',
            'nip' => '1234567890123456',
            'nama_pegawai' => 'John Doe',
            'jenis_pegawai' => 'PNS',
            'jenis_sk' => 'SK-001',
            'nomor_sk' => 'SK-001/2023',
            'grade' => 'A',
            'jenis_tukin' => 'Tukin Bulanan',
            'kotor' => 5000000,
            'potongan' => 500000,
            'bersih' => 4500000,
            'pajak' => 100000,
            'tunj_pajak' => 50000,
            'bersih_2' => 4350000,
            'kdbankspan' => '1234567890',
            'rekening' => '1234567890123456789',
            'nama_rekening' => 'John Doe',
            'nama_bank' => 'Bank ABC',
            'bulan_awal' => 1,
            'tahun_awal' => 2023,
            'bulan_akhir' => 1,
            'tahun_akhir' => 2023,
            'kali_pembayaran' => 1,
        ]);
    }
}
