<?php

namespace App\Http\Controllers;

use App\Models\Header;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Data statistik ringkasan
        $totalHeaders = Header::count();
        $totalRecipients = DB::table('tukin')->count();
        $tniCount = DB::table('tukin')->where('tni_pns', 'TNI')->count();
        $pnsCount = DB::table('tukin')->where('tni_pns', 'PNS')->count();

        // Data untuk chart total tukin per bulan
        $monthlyData = Header::select(
            DB::raw('YEAR(tanggal) as year'),
            DB::raw('MONTH(tanggal) as month'),
            DB::raw('COUNT(id) as total_headers'),
            DB::raw('SUM((SELECT COUNT(*) FROM tukin WHERE tukin.header_id = headers.id)) as total_recipients')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Data untuk chart TNI vs PNS
        $tniPnsData = DB::table('tukin')
            ->select(
                'tni_pns',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('tni_pns')
            ->get();

        // Data untuk top satker dengan breakdown TNI/PNS
        $topSatkers = DB::table('headers')
            ->join('satkers', 'headers.kode_satker', '=', 'satkers.kode_satker')
            ->leftJoin('tukin', 'tukin.header_id', '=', 'headers.id')
            ->select(
                'satkers.kode_satker',
                'satkers.nama_satker',
                DB::raw('COUNT(DISTINCT headers.id) as total_headers'),
                DB::raw('COUNT(tukin.id) as total_recipients'),
                DB::raw('SUM(CASE WHEN tukin.tni_pns = "TNI" THEN 1 ELSE 0 END) as tni_count'),
                DB::raw('SUM(CASE WHEN tukin.tni_pns = "PNS" THEN 1 ELSE 0 END) as pns_count')
            )
            ->groupBy('satkers.kode_satker', 'satkers.nama_satker')
            ->orderBy('total_recipients', 'desc')
            ->take(10)
            ->get()
            ->map(function ($satker) {
                $satker->tni_percentage = $satker->total_recipients > 0 
                    ? round(($satker->tni_count / $satker->total_recipients) * 100, 1) 
                    : 0;
                $satker->pns_percentage = $satker->total_recipients > 0 
                    ? round(($satker->pns_count / $satker->total_recipients) * 100, 1) 
                    : 0;
                return $satker;
            });

        // Aktivitas terakhir
        $recentActivities = DB::table('activity_logs')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Data untuk perbandingan TNI vs PNS per satker (untuk tabel lengkap)
        $satkerComparison = DB::table('headers')
            ->join('satkers', 'headers.kode_satker', '=', 'satkers.kode_satker')
            ->leftJoin('tukin', 'tukin.header_id', '=', 'headers.id')
            ->select(
                'satkers.kode_satker',
                'satkers.nama_satker',
                DB::raw('COUNT(DISTINCT headers.id) as total_headers'),
                DB::raw('COUNT(tukin.id) as total_recipients'),
                DB::raw('SUM(CASE WHEN tukin.tni_pns = "TNI" THEN 1 ELSE 0 END) as tni_count'),
                DB::raw('SUM(CASE WHEN tukin.tni_pns = "PNS" THEN 1 ELSE 0 END) as pns_count')
            )
            ->groupBy('satkers.kode_satker', 'satkers.nama_satker')
            ->having('total_recipients', '>', 0)
            ->orderBy('total_recipients', 'desc')
            ->get()
            ->map(function ($satker) {
                $satker->tni_percentage = $satker->total_recipients > 0 
                    ? round(($satker->tni_count / $satker->total_recipients) * 100, 1) 
                    : 0;
                $satker->pns_percentage = $satker->total_recipients > 0 
                    ? round(($satker->pns_count / $satker->total_recipients) * 100, 1) 
                    : 0;
                return $satker;
            });

        // Data statistik tambahan
        $additionalStats = [
            'avg_recipients_per_header' => $totalHeaders > 0 ? round($totalRecipients / $totalHeaders, 2) : 0,
            'active_satkers' => DB::table('headers')->distinct('kode_satker')->count(),
            'current_month_headers' => Header::whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count(),
            'last_month_headers' => Header::whereMonth('tanggal', now()->subMonth()->month)
                ->whereYear('tanggal', now()->subMonth()->year)
                ->count(),
        ];

        // Calculate growth percentage
        $additionalStats['growth_percentage'] = $additionalStats['last_month_headers'] > 0 
            ? round((($additionalStats['current_month_headers'] - $additionalStats['last_month_headers']) / $additionalStats['last_month_headers']) * 100, 1)
            : 0;

        ActivityLogService::log('view_dashboard', 'Mengakses dashboard');

        return view('dashboard', compact(
            'totalHeaders',
            'totalRecipients',
            'tniCount',
            'pnsCount',
            'monthlyData',
            'tniPnsData',
            'topSatkers',
            'recentActivities',
            'satkerComparison',
            'additionalStats'
        ));
    }

    /**
     * Get dashboard data via AJAX for real-time updates
     */
    public function getDashboardData()
    {
        $data = [
            'totalHeaders' => Header::count(),
            'totalRecipients' => DB::table('tukin')->count(),
            'tniCount' => DB::table('tukin')->where('tni_pns', 'TNI')->count(),
            'pnsCount' => DB::table('tukin')->where('tni_pns', 'PNS')->count(),
        ];

        return response()->json($data);
    }

    /**
     * Get monthly chart data
     */
    public function getMonthlyChartData()
    {
        $monthlyData = Header::select(
            DB::raw('YEAR(tanggal) as year'),
            DB::raw('MONTH(tanggal) as month'),
            DB::raw('COUNT(id) as total_headers'),
            DB::raw('SUM((SELECT COUNT(*) FROM tukin WHERE tukin.header_id = headers.id)) as total_recipients')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json($monthlyData);
    }

    /**
     * Get TNI vs PNS chart data
     */
    public function getTniPnsChartData()
    {
        $tniPnsData = DB::table('tukin')
            ->select(
                'tni_pns',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('tni_pns')
            ->get();

        return response()->json($tniPnsData);
    }

    /**
     * Export dashboard data to Excel
     */
    public function exportDashboard()
    {
        // Implementation for Excel export
        // You can use Laravel Excel package here
        
        return response()->json(['message' => 'Export functionality to be implemented']);
    }
}