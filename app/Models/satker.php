<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;

    protected $table = 'satkers';
    
    protected $fillable = [
        'kode_satker',
        'nama_satker'
    ];

    public function headers()
    {
        return $this->hasMany(Header::class, 'kode_satker');
    }
}