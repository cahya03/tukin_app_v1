<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Header;
use App\Models\Satker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\ExcelImportService;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;
use Carbon\Carbon;

class HeaderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view,header')->only('show');
    }

    public function index()
    {
        // Filter berdasarkan role user
        $headers = Header::query()
            ->when(Auth::user()->role === 'juru_bayar', function ($query) {
                $query->where('kode_satker', Auth::user()->satker->kode_satker);
            })
            ->with(['satker' => function ($query) {
                $query->select('kode_satker', 'nama_satker');
            }])
            ->latest()
            ->paginate(10);

        // Filter satker yang bisa dipilih berdasarkan role
        $satkers = Satker::query()
            ->when(Auth::user()->role === 'juru_bayar', function ($query) {
                return $query->where('kode_satker', Auth::user()->kode_satker);
            })
            ->select('kode_satker', 'nama_satker')
            ->get();

        // Log aktivitas melihat daftar header
        ActivityLogService::log(
            'view_headers_list',
            'Melihat daftar header'
        );
        return view('headers.create', compact('headers', 'satkers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_header' => 'required|string|max:255',
            'deskripsi_header' => 'required|string|max:255',
            'kode_satker' => Auth::user()->role === 'admin'
                ? 'required|exists:satkers,kode_satker'
                : 'required|in:' . Auth::user()->kode_satker,
            'tanggal' => 'required|date',
            'file_tni' => 'required|file|mimes:zip,xls,xlsx|max:2048',
            'file_pns' => 'required|file|mimes:zip,xls,xlsx|max:2048',
            'file_pdf' => 'required|file|mimes:pdf|max:2048'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {

            DB::beginTransaction();
            // Simpan file
            $fileTNI = $request->file('file_tni');
            $filePNS = $request->file('file_pns');
            $filePDF = $request->file('file_pdf');
            $filePathTNI = $this->storeFileWithCustomName($fileTNI, 'tni');
            $filePathPNS = $this->storeFileWithCustomName($filePNS, 'pns');
            $filePathPDF = $this->storeFileWithCustomName($filePDF, 'pdf');
            if (!$filePathTNI || !$filePathPNS) {
                throw new \Exception('File gagal disimpan.');
            }
            // Simpan data ke database
            $header = Header::create([
                'nama_header' => $request->nama_header,
                'deskripsi_header' => $request->deskripsi_header,
                'kode_satker' => Auth::user()->role === 'admin'
                    ? $request->kode_satker
                    : Auth::user()->kode_satker,
                'tanggal' => $request->tanggal,
                'file_tni_path' => $filePathTNI,
                'file_pns_path' => $filePathPNS,
                'file_pdf_path' => $filePathPDF,
                'created_by' => Auth::id(), // Catat pembuat header
            ]);

            if ($filePathTNI && $filePathPNS) {
                $excelImportService = new ExcelImportService();
                $excelImportService->importFromHeader($header);
            }
            DB::commit();
            // Log aktivitas membuat header berhasil
            ActivityLogService::logCreateHeader([
                'id' => $header->id,
                'title' => $header->nama_header
            ]);
            return redirect()->back()->with('sukses', 'Header berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log aktivitas membuat header gagal
            ActivityLogService::log(
                ActivityLogService::CREATE_HEADER,
                'Gagal membuat header: ' . $request->nama_header,
                $request,
                null,
                'failed',
                $e->getMessage()
            );
            return redirect()->back()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Request $request, Header $header)
    {
        // Eager load dengan urutan yang benar
        $header->load([
            'satker',
            'tukins' => function ($query) use ($request) {
                $query->orderBy('tni_pns')->orderBy('nama_pegawai');

                // Tambahkan pencarian jika ada
                if ($request->has('search')) {
                    $query->where(function ($q) use ($request) {
                        $q->where('nama_pegawai', 'like', "%{$request->search}%")
                            ->orWhere('nip', 'like', "%{$request->search}%")
                            ->orWhere('nomor_tukin', 'like', "%{$request->search}%");
                    });
                }
            }
        ]);

        // Query terpisah untuk pagination dengan search
        $tniQuery = $header->tukins()
            ->where('tni_pns', 'TNI')
            ->orderBy('nama_pegawai');

        $pnsQuery = $header->tukins()
            ->where('tni_pns', 'PNS')
            ->orderBy('nama_pegawai');

        // Terapkan pencarian
        if ($request->has('search')) {
            $searchTerm = $request->search;

            $tniQuery->where(function ($query) use ($searchTerm) {
                $query->where('nama_pegawai', 'like', "%{$searchTerm}%")
                    ->orWhere('nip', 'like', "%{$searchTerm}%")
                    ->orWhere('nomor_tukin', 'like', "%{$searchTerm}%");
            });

            $pnsQuery->where(function ($query) use ($searchTerm) {
                $query->where('nama_pegawai', 'like', "%{$searchTerm}%")
                    ->orWhere('nip', 'like', "%{$searchTerm}%")
                    ->orWhere('nomor_tukin', 'like', "%{$searchTerm}%");
            });
        }

        // Lakukan pagination SETELAH semua kondisi where
        $tniData = $tniQuery->paginate(10, ['*'], 'tni_page');
        $pnsData = $pnsQuery->paginate(10, ['*'], 'pns_page');

        // Log aktivitas
        ActivityLogService::logViewHeader(
            $header->id,
            $header->nama_header,
        );

        return view('headers.show', compact('header', 'tniData', 'pnsData'));
    }

    public function edit(Header $header)
    {
        // Get satkers based on user role
        $satkers = Satker::query()
            ->when(Auth::user()->role === 'juru_bayar', function ($query) {
                return $query->where('kode_satker', Auth::user()->kode_satker);
            })
            ->select('kode_satker', 'nama_satker')
            ->get();

        ActivityLogService::log(
            'access_edit_header_form',
            'Mengakses form edit header: ' . $header->title,
            null,
            ['header_id' => $header->id]
        );
        return view('headers.edit', compact('header', 'satkers'));
    }

    public function update(Request $request, Header $header)
    {
        // Dynamic validation based on role
        $validator = Validator::make($request->all(), [
            'nama_header' => 'required|string|max:255',
            'kode_satker' => Auth::user()->role === 'admin'
                ? 'required|exists:satkers,kode_satker'
                : 'required|in:' . Auth::user()->kode_satker,
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            ActivityLogService::log(
                ActivityLogService::UPDATE_HEADER,
                'Gagal mengupdate header ID: ' . $header->id,
                $request,
                ['header_id' => $header->id],
                'failed',
            );
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // Log aktivitas sebelum update
        $oldData = $header->only([
            'nama_header',
            'kode_satker',
            'tanggal',
        ]);

        $header->update([
            'nama_header' => $request->nama_header,
            'kode_satker' => Auth::user()->role === 'admin'
                ? $request->kode_satker
                : Auth::user()->kode_satker,
            'tanggal' => $request->tanggal,
        ]);
        $changes = [];
        $newData = $request->only([
            'nama_header',
            'kode_satker',
            'tanggal',
        ]);
        foreach ($newData as $field => $newValue) {
            if ($oldData[$field] !== $newValue) {
                $changes[$field] = [
                    'old' => $oldData[$field],
                    'new' => $newValue
                ];
            }
        }
        // Log aktivitas update header
        ActivityLogService::logUpdateHeader($header->id, $changes);
        return redirect()->route('headers.index')
            ->with('success', 'Header berhasil diperbarui');
    }

    public function destroy(Header $header)
    {
        $fileTniPath = str_replace('storage/', '', $header->file_tni_path);
        $filePnsPath = str_replace('storage/', '', $header->file_pns_path);
        $filePdfPath = str_replace('storage/', '', $header->file_pdf_path);
        // Delete related files
        Storage::disk('public')->delete([$fileTniPath, $filePnsPath, $filePdfPath]);

        $header->delete();
        // Log aktivitas menghapus header
        ActivityLogService::logDeleteHeader($header->id, $header->nama_header);
        return redirect()->route('headers.index')
            ->with('success', 'Header berhasil dihapus');
    }

    // public function dashboard()
    // {
    //     // Data statistik ringkasan
    //     $totalHeaders = Header::count();
    //     $totalRecipients = DB::table('tukin')->count();
    //     $tniCount = DB::table('tukin')->where('tni_pns', 'TNI')->count();
    //     $pnsCount = DB::table('tukin')->where('tni_pns', 'PNS')->count();

    //     // Data untuk chart total tukin per bulan
    //     $monthlyData = Header::select(
    //         DB::raw('YEAR(tanggal) as year'),
    //         DB::raw('MONTH(tanggal) as month'),
    //         DB::raw('COUNT(id) as total_headers'),
    //         DB::raw('SUM((SELECT COUNT(*) FROM tukin WHERE tukin.header_id = headers.id)) as total_recipients')
    //     )
    //         ->groupBy('year', 'month')
    //         ->orderBy('year', 'asc')
    //         ->orderBy('month', 'asc')
    //         ->get();

    //     // Data untuk chart TNI vs PNS
    //     $tniPnsData = DB::table('tukin')
    //         ->select(
    //             'tni_pns',
    //             DB::raw('COUNT(*) as total')
    //         )
    //         ->groupBy('tni_pns')
    //         ->get();

    //     // Data untuk top satker
    //     $topSatkers = DB::table('headers')
    //         ->join('satkers', 'headers.kode_satker', '=', 'satkers.kode_satker')
    //         ->select(
    //             'satkers.nama_satker',
    //             DB::raw('COUNT(headers.id) as total_headers'),
    //             DB::raw('(SELECT COUNT(*) FROM tukin WHERE tukins.header_id IN 
    //                  (SELECT id FROM headers WHERE kode_satker = satkers.kode_satker)) as total_recipients')
    //         )
    //         ->groupBy('satkers.kode_satker', 'satkers.nama_satker')
    //         ->orderBy('total_recipients', 'desc')
    //         ->take(10)
    //         ->get();

    //     // Aktivitas terakhir
    //     $recentActivities = DB::table('activity_logs')
    //         ->orderBy('created_at', 'desc')
    //         ->take(5)
    //         ->get();

    //     ActivityLogService::log('view_dashboard', 'Mengakses dashboard');

    //     return view('dashboard', compact(
    //         'totalHeaders',
    //         'totalRecipients',
    //         'tniCount',
    //         'pnsCount',
    //         'monthlyData',
    //         'tniPnsData',
    //         'topSatkers',
    //         'recentActivities'
    //     ));
    // }


    // private function storeFileWithCustomName($file, $prefix)
    // {
    //     try {
    //         $extension = $file->getClientOriginalExtension();
    //         $fileName = $prefix . '-' . time() . '_' . Str::random(10) . '.' . $extension;
    //         return $file->storeAs('tukin', $fileName, 'public');
    //     } catch (\Exception $e) {
    //         Log::error('File upload error: ' . $e->getMessage());
    //         return null;
    //     }
    // }
    public function storeFileWithCustomName($file, $tipe)
    {
        try {
            $tanggal = Carbon::parse(request()->tanggal);
            $bulan = $tanggal->format('m');
            $tahun = $tanggal->format('Y');
            $kodeSatker = Auth::user()->role === 'admin'
                ? request()->kode_satker
                : Auth::user()->kode_satker;

            $namaHeader = Str::slug(request()->nama_header);
            $timestamp = now()->format('YmdHis');
            $ext = $file->getClientOriginalExtension();

            $filename = "{$kodeSatker}_{$namaHeader}_{$tipe}_{$timestamp}.{$ext}";
            $folder = "tukin/{$tahun}/{$bulan}";

            $path = $file->storeAs($folder, $filename, 'public');

            return 'storage/' . $path;
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return null;
        }
    }
}
