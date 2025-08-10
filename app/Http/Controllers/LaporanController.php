<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Header;
use App\Models\Satker;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;

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
    $user = Auth::user();
    $name = $user->name ?? 'unknown';

    try {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'bulan_awal' => 'required|integer|min:1|max:12',
            'bulan_akhir' => 'required|integer|min:1|max:12',
            'tahun_awal' => 'required|integer|min:2000|max:' . date('Y'),
            'tahun_akhir' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            ActivityLogService::log(
                ActivityLogService::CETAK_LAPORAN,
                'User '.$name.' gagal mencetak laporan: validasi input gagal',
                $request
            );
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil periode
        $bulanAwal = (int) $request->bulan_awal;
        $bulanAkhir = (int) $request->bulan_akhir;
        $tahunAwal = (int) $request->tahun_awal;
        $tahunAkhir = (int) $request->tahun_akhir;

        $periodeAwal = $tahunAwal * 100 + $bulanAwal;
        $periodeAkhir = $tahunAkhir * 100 + $bulanAkhir;

        if ($periodeAwal > $periodeAkhir) {
            ActivityLogService::log(
                ActivityLogService::CETAK_LAPORAN,
                'User '.$name.' gagal mencetak laporan: periode akhir lebih awal dari awal',
                $request
            );
            return redirect()->back()
                ->withErrors(['Periode akhir tidak boleh lebih awal dari periode awal.'])
                ->withInput();
        }

        // Cek role user
        $isAdmin = $user->role === 'admin';
        $kodeSatkerUser = $user->kode_satker ?? null;

        // Query dasar
        $sql = "
            SELECT 
                s.kode_satker,
                s.nama_satker,
                MONTH(h.tanggal) AS bulan,
                YEAR(h.tanggal) AS tahun,
                COUNT(DISTINCT t.nip) AS jumlah_personel,
                SUM(t.bersih) AS total_tukin
            FROM tukin t
            JOIN headers h ON h.id = t.header_id
            JOIN satkers s ON s.kode_satker = h.kode_satker
            WHERE (YEAR(h.tanggal) * 100 + MONTH(h.tanggal)) BETWEEN ? AND ?
        ";

        $params = [$periodeAwal, $periodeAkhir];

        // Kalau bukan admin → filter satker sesuai user
        if (!$isAdmin) {
            if (!$kodeSatkerUser) {
                ActivityLogService::log(
                    ActivityLogService::CETAK_LAPORAN,
                    'User '.$name.' gagal mencetak laporan: satker tidak terdeteksi',
                    $request
                );
                return redirect()->back()
                    ->withErrors(['Kode satker tidak ditemukan untuk akun Anda.'])
                    ->withInput();
            }
            $sql .= " AND s.kode_satker = ? ";
            $params[] = $kodeSatkerUser;
        }

        $sql .= "
            GROUP BY s.kode_satker, s.nama_satker, tahun, bulan
            ORDER BY s.kode_satker, tahun, bulan
        ";

        $data = DB::select($sql, $params);

        if (empty($data)) {
            ActivityLogService::log(
                ActivityLogService::CETAK_LAPORAN,
                'User '.$name.' gagal mencetak laporan: tidak ada data',
                $request
            );
            return redirect()->back()
                ->withErrors(['Tidak ada data ditemukan untuk rentang waktu yang dipilih.'])
                ->withInput();
        }

        $grouped = collect($data)->groupBy('kode_satker');

        ActivityLogService::log(
            ActivityLogService::CETAK_LAPORAN,
            'User '.$name.' berhasil mencetak laporan periode ' . $bulanAwal . '/' . $tahunAwal . ' - ' . $bulanAkhir . '/' . $tahunAkhir,
            $request
        );

        $pdf = PDF::loadView('pdf.laporan', [
            'data' => $grouped,
            'periode' => [
                'bulan_awal' => $bulanAwal,
                'bulan_akhir' => $bulanAkhir,
                'tahun_awal' => $tahunAwal,
                'tahun_akhir' => $tahunAkhir,
            ],
        ]);

        return $pdf->stream('laporan-rekap-tukin.pdf');

    } catch (\Exception $e) {
        ActivityLogService::log(
            ActivityLogService::CETAK_LAPORAN,
            'User '.$name.' gagal mencetak laporan: ' . $e->getMessage(),
            $request
        );
        return redirect()->back()
            ->withErrors(['Terjadi kesalahan saat membuat laporan. Silakan coba lagi.'])
            ->withInput();
    }
}
}
