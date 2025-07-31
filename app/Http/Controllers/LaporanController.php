<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Header;
use App\Models\Satker;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Generate a PDF report for the specified date range.
     *
     */

    public function form()
    {
        return view('laporan.form');
    }
    public function laporanPDF(Request $request)
    {
        $data = DB::select("
            SELECT 
                s.kode_satker,
                s.nama_satker,
                MONTH(h.tanggal) AS bulan,
                COUNT(DISTINCT t.nip) AS jumlah_personel,
                SUM(t.bersih) AS total_tukin
            FROM tukin t
            JOIN headers h ON h.id = t.header_id
            JOIN satkers s ON s.kode_satker = h.kode_satker
            GROUP BY s.kode_satker, s.nama_satker, bulan
            ORDER BY s.kode_satker, bulan
        ");

        // Grouping by Satker
        $grouped = collect($data)->groupBy('kode_satker');

        $pdf = PDF::loadView('pdf.laporan', [
            'data' => $grouped
        ]);

        return $pdf->stream('laporan-rekap-tukin.pdf');
    }
}
