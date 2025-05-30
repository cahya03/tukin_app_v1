<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tukin extends Model
{
    use HasFactory;

    protected $table = 'tukin';

    protected $fillable = [
        'id',
        'id_tukin',
        'id_proses',
        'nomor_tukin',
        'kdsatker',
        'nip',
        'nama_pegawai',
        'jenis_pegawai',
        'jenis_sk',
        'nomor_sk',
        'grade',
        'jenis_tukin',
        'kotor',
        'potongan',
        'bersih',
        'pajak',
        'tunj_pajak',
        'bersih_2',
        'kdbankspan',
        'rekening',
        'nama_rekening',
        'nama_bank',
        'bulan_awal',
        'tahun_awal',
        'bulan_akhir',
        'tahun_akhir',
        'kali_pembayaran',
        'nomor_tukin_lama',
        'nomor_tukin_baru',
        'tni_pns',
        'header_id',
    ];
    public function header()
{
    return $this->belongsTo(Header::class);
}
}
