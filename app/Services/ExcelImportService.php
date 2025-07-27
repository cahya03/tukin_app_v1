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
            $pathTNI = storage_path('app/public/' . str_replace('storage/', '', $header->file_tni_path));
            Excel::import(new TukinImport('TNI', $header->id), $pathTNI);
        }

        // Import data PNS
        if ($header->file_pns_path) {
            $pathPNS = storage_path('app/public/' . str_replace('storage/', '', $header->file_pns_path));
            Excel::import(new TukinImport('PNS', $header->id), $pathPNS);
        }
    }
}