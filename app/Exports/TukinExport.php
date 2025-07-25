<?php

namespace App\Exports;

use App\Models\Tukin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TukinExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Tukin::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nomor Tukin',
            'NIP',
            'Nama Pegawai',
            'Jenis Pegawai',
            'Grade',
            'Bersih',
            // Add other columns as needed
        ];
    }
}