<?php

namespace App\Imports;

use App\Models\Tukin;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TukinImport implements ToModel, WithHeadingRow
{
    protected $tniPns;

    public function __construct(string $tniPns = 'TNI')
    {
        $this->tniPns = strtoupper($tniPns); // Pastikan uppercase: TNI / PNS
    }

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Tukin([
            'id_tukin' => $row['id_tukin'],
            'id_proses' => $row['id_proses'],
            'tni_pns' => $this->tniPns,
            'nomor_tukin' => $row['nomor_tukin'],
            'kdsatker' => $row['kdsatker'],
            'nip' => $row['nip'],
            'nama_pegawai' => $row['nama_pegawai'],
            'jenis_pegawai' => $row['jenis_pegawai'],
            'jenis_sk' => $row['jenis_sk'],
            'nomor_sk' => $row['nomor_sk'],
            'grade' => $row['grade'],
            'jenis_tukin' => $row['jenis_tukin'],
            'kotor' => $row['kotor'],
            'potongan' => $row['potongan'],
            'bersih' => $row['bersih'],
            'pajak' => $row['pajak'],
            'tunj_pajak' => $row['tunj_pajak'],
            'bersih_2' => $row['bersih_2'],
            'kdbankspan' => $row['kdbankspan'],
            'rekening' => $row['rekening'],
            'nama_rekening' => $row['nama_rekening'],
            'nama_bank' => $row['nama_bank'],
            'bulan_awal' => $row['bulan_awal'],
            'tahun_awal' => $row['tahun_awal'],
            'bulan_akhir' => $row['bulan_akhir'],
            'tahun_akhir' => $row['tahun_akhir'],
            'kali_pembayaran' => $row['kali_pembayaran'],
            'nomor_tukin_lama' => $row['nomor_tukin_lama'],
            'nomor_tukin_baru' => $row['nomor_tukin_baru'],
        ]);
    }
}
