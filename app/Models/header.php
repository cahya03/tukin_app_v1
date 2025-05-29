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
        'file_path',
        'created_by',
    ];
    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }
    public function tukins()
    {
        return $this->hasMany(Tukin::class);
    }
}
