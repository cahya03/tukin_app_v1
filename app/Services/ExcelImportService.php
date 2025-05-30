<?php

namespace App\Services;

use App\Models\Header;
use App\Models\Tukin;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TukinImport;
class ExcelImportService
{
    public function importFromHeader(Header $header)
    {
        // Import data TNI
        if ($header->file_tni_path) {
            Excel::import(new TukinImport('TNI', $header->id), storage_path('app/public/'.$header->file_tni_path));
        }

        // Import data PNS
        if ($header->file_pns_path) {
            Excel::import(new TukinImport('PNS',$header->id), storage_path('app/public/'.$header->file_pns_path));
        }
    }
}