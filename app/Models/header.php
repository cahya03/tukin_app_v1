<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    use HasFactory;

    protected $table = 'headers';

    protected $fillable = [
        'nama_header',
        'deskripsi_header',
        'kode_satker',
        'tanggal',
        'file_tni_path',
        'file_pns_path',
        'created_by',
    ];
    public function satker()
    {
        return $this->belongsTo(Satker::class, 'kode_satker', 'kode_satker');
    }
    public function tukins()
    {
        return $this->hasMany(Tukin::class);
    }
    public function creator() 
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
