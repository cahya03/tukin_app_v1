<?php

namespace App\Http\Controllers;

use App\Models\Tukin;
use App\Models\Header;
use App\Models\Satker;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            return $this->adminDashboard($request);
        }

        return $this->juruBayarDashboard();
    }

    protected function adminDashboard(request $request)
    {
        $year = $request->input('year', now()->year);

        $stats = $this->getAdminStatistics();
        $monthlyData = $this->getMonthlyData();
        $tniPnsData = $this->getTniPnsData();
        $topSatkers = $this->getTopSatkers(10);
        $satkerComparison = $this->getSatkerComparison();
        $recentActivities = $this->getRecentActivities(5);
        $additionalStats = $this->getAdditionalStats();
        $tunkinUploadStatus = $this->getTunkinUploadStatus($year);
        $headers = Header::latest()->get();
        $name = Auth::user()->name ?? 'unknown';

        ActivityLogService::log('view_admin_dashboard', 'User '.$name.' Mengakses dashboard admin');

        return view('dashboard', compact(
            'stats',
            'monthlyData',
            'tniPnsData',
            'topSatkers',
            'recentActivities',
            'satkerComparison',
            'additionalStats',
            'tunkinUploadStatus',
            'headers',
            'year'
        ));
    }

    protected function juruBayarDashboard()
    {
        $satkerId = Auth::user()->kode_satker;

        $stats = $this->getJuruBayarStatistics($satkerId);
        $pangkatData = $this->getPangkatData($satkerId);
        $paymentHistory = $this->getPaymentHistory($satkerId, 6);
        $recentHeaders = $this->getRecentHeaders($satkerId, 5);

        ActivityLogService::log('view_jurubayar_dashboard', 'Mengakses dashboard juru bayar');

        return view('dashboard', compact(
            'stats',
            'pangkatData',
            'paymentHistory',
            'recentHeaders'
        ));
    }

    protected function getAdminStatistics()
    {
        return [
            'totalHeaders' => Header::count(),
            'totalRecipients' => Tukin::count(),
            'tniCount' => Tukin::where('tni_pns', 'TNI')->count(),
            'pnsCount' => Tukin::where('tni_pns', 'PNS')->count(),
            'totalNominal' => Tukin::sum('bersih'),
        ];
    }

    protected function getJuruBayarStatistics($satkerId)
    {
        return [
            'totalPenerima' => Tukin::whereHas('header', function ($q) use ($satkerId) {
                $q->where('kode_satker', $satkerId);
            })->count(),
            'tniCount' => Tukin::where('tni_pns', 'TNI')
                ->whereHas('header', function ($q) use ($satkerId) {
                    $q->where('kode_satker', $satkerId);
                })->count(),
            'pnsCount' => Tukin::where('tni_pns', 'PNS')
                ->whereHas('header', function ($q) use ($satkerId) {
                    $q->where('kode_satker', $satkerId);
                })->count(),
            'totalNominal' => Tukin::whereHas('header', function ($q) use ($satkerId) {
                $q->where('kode_satker', $satkerId);
            })->sum('bersih'),
        ];
    }

    protected function getMonthlyData()
    {
        return Header::select(
            DB::raw('YEAR(headers.tanggal) as year'),
            DB::raw('MONTH(headers.tanggal) as month'),
            DB::raw('COUNT(DISTINCT headers.id) as total_headers'),
            DB::raw('COUNT(tukin.id) as total_recipients'),
            DB::raw('COALESCE(SUM(tukin.bersih), 0) as total_nominal')
        )
            ->leftJoin('tukin', 'tukin.header_id', '=', 'headers.id')
            ->groupBy(DB::raw('YEAR(headers.tanggal)'), DB::raw('MONTH(headers.tanggal)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }

    protected function getTniPnsData()
    {
        return Tukin::select(
            'tni_pns',
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(bersih) as total_nominal')
        )
            ->groupBy('tni_pns')
            ->get();
    }

    protected function getPangkatData($satkerId)
    {
        return Tukin::whereHas('header', function ($q) use ($satkerId) {
            $q->where('kode_satker', $satkerId);
        })
            ->select(
                'grade',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(bersih) as total_nominal')
            )
            ->groupBy('grade')
            ->orderBy('total', 'desc')
            ->get();
    }

    protected function getPaymentHistory($satkerId, $months = 6)
    {
        return Header::withCount(['tukins as total_recipients'])
            ->withSum('tukins', 'bersih')
            ->select(
                DB::raw('YEAR(tanggal) as year'),
                DB::raw('MONTH(tanggal) as month'),
                DB::raw('COUNT(id) as total_headers')
            )
            ->where('kode_satker', $satkerId)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit($months)
            ->get()
            ->map(function ($header) {
                return [
                    'year' => $header->year,
                    'month' => $header->month,
                    'total_headers' => $header->total_headers,
                    'total_recipients' => $header->total_recipients,
                    'total_nominal' => $header->tukins_sum_bersih
                ];
            });
    }

    protected function getTopSatkers($limit = 10)
    {
        return Header::join('satkers', 'headers.kode_satker', '=', 'satkers.kode_satker')
            ->select(
                'satkers.kode_satker',
                'satkers.nama_satker',
                DB::raw('COUNT(DISTINCT headers.id) as total_headers'),
                DB::raw('(SELECT COUNT(*) FROM tukin WHERE tukin.header_id IN 
                        (SELECT id FROM headers WHERE kode_satker = satkers.kode_satker)) as total_recipients'),
                DB::raw('(SELECT SUM(bersih) FROM tukin WHERE tukin.header_id IN 
                        (SELECT id FROM headers WHERE kode_satker = satkers.kode_satker)) as total_nominal'),
                DB::raw('(SELECT COUNT(*) FROM tukin WHERE tukin.tni_pns = "TNI" AND tukin.header_id IN 
                        (SELECT id FROM headers WHERE kode_satker = satkers.kode_satker)) as tni_count'),
                DB::raw('(SELECT COUNT(*) FROM tukin WHERE tukin.tni_pns = "PNS" AND tukin.header_id IN 
                        (SELECT id FROM headers WHERE kode_satker = satkers.kode_satker)) as pns_count')
            )
            ->groupBy('satkers.kode_satker', 'satkers.nama_satker')
            ->orderBy('total_nominal', 'desc')
            ->take($limit)
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
    }

    protected function getSatkerComparison()
    {
        return Header::join('satkers', 'headers.kode_satker', '=', 'satkers.kode_satker')
            ->select(
                'satkers.kode_satker',
                'satkers.nama_satker',
                DB::raw('COUNT(DISTINCT headers.id) as total_headers'),
                DB::raw('(SELECT COUNT(*) FROM tukin WHERE tukin.header_id IN 
                        (SELECT id FROM headers WHERE kode_satker = satkers.kode_satker)) as total_recipients'),
                DB::raw('(SELECT SUM(bersih) FROM tukin WHERE tukin.header_id IN 
                        (SELECT id FROM headers WHERE kode_satker = satkers.kode_satker)) as total_nominal'),
                DB::raw('(SELECT COUNT(*) FROM tukin WHERE tukin.tni_pns = "TNI" AND tukin.header_id IN 
                        (SELECT id FROM headers WHERE kode_satker = satkers.kode_satker)) as tni_count'),
                DB::raw('(SELECT COUNT(*) FROM tukin WHERE tukin.tni_pns = "PNS" AND tukin.header_id IN 
                        (SELECT id FROM headers WHERE kode_satker = satkers.kode_satker)) as pns_count')
            )
            ->groupBy('satkers.kode_satker', 'satkers.nama_satker')
            ->having('total_recipients', '>', 0)
            ->orderBy('total_nominal', 'desc')
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
    }

    protected function getRecentHeaders($satkerId, $limit = 5)
    {
        return Header::withCount([
            'tukins as total_recipients',
            'tukins as total_nominal' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(bersih), 0)'));
            }
        ])
            ->where('kode_satker', $satkerId)
            ->orderBy('tanggal', 'desc')
            ->limit($limit)
            ->get();
    }

    protected function getRecentActivities($limit = 5)
    {
        return DB::table('activity_logs')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    protected function getAdditionalStats()
    {
        $currentMonthHeaders = Header::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $lastMonthHeaders = Header::whereMonth('tanggal', now()->subMonth()->month)
            ->whereYear('tanggal', now()->subMonth()->year)
            ->count();

        return [
            'avg_recipients_per_header' => Header::count() > 0
                ? round(Tukin::count() / Header::count(), 2)
                : 0,
            'avg_nominal_per_header' => Header::count() > 0
                ? round(Tukin::sum('bersih') / Header::count(), 2)
                : 0,
            'active_satkers' => DB::table('headers')->distinct('kode_satker')->count(),
            'current_month_headers' => $currentMonthHeaders,
            'last_month_headers' => $lastMonthHeaders,
            'growth_percentage' => $lastMonthHeaders > 0
                ? round((($currentMonthHeaders - $lastMonthHeaders) / $lastMonthHeaders * 100), 1)
                : 0,
        ];
    }
    
    protected function getTunkinUploadStatus($tahun = null)
    {
        $tahun = $tahun ?? now()->year;
        $satkers = Satker::orderBy('kode_satker')->get();

        $uploadStatus = $satkers->map(function ($satker) use ($tahun) {
        $statusPerBulan = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $exists = Header::where('kode_satker', $satker->kode_satker)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->exists();

            $statusPerBulan[$bulan] = $exists;
        }
        return [
            'nama_satker' => $satker->nama_satker,
            'kode_satker' => $satker->kode_satker,
            'bulan_status' => $statusPerBulan,
        ];

        });
        return $uploadStatus;
    }
    public function getDashboardData()
    {
        if (Auth::user()->role === 'admin') {
            $data = $this->getAdminStatistics();
        } else {
            $data = $this->getJuruBayarStatistics(Auth::user()->kode_satker);
        }

        return response()->json($data);
    }

    public function getMonthlyChartData()
    {
        if (Auth::user()->role === 'admin') {
            $data = $this->getMonthlyData();
        } else {
            $data = $this->getPaymentHistory(Auth::user()->kode_satker, 12);
        }

        return response()->json($data);
    }

    public function getTniPnsChartData()
    {
        if (Auth::user()->role === 'admin') {
            $data = $this->getTniPnsData();
        } else {
            $data = $this->getPangkatData(Auth::user()->kode_satker);
        }

        return response()->json($data);
    }


}
