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
            'file_pns' => 'required|file|mimes:zip,xls,xlsx|max:2048'
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
            $filePathTNI = $this->storeFileWithCustomName($fileTNI, 'tni');
            $filePathPNS = $this->storeFileWithCustomName($filePNS, 'pns');

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
                'created_by' => Auth::id(), // Catat pembuat header
            ]);

            if ($filePathTNI && $filePathPNS) {
                $excelImportService = new ExcelImportService();
                $excelImportService->importFromHeader($header);
            }
            DB::commit();
            return redirect()->back()->with('sukses', 'Header berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Header $header)
    {
        // Eager load satker dan data tukin terkait
        $header->load([
            'satker',
            'tukins' => function ($query) {
                $query->orderBy('tni_pns')->orderBy('nama_pegawai');
            }
        ]);

        // Pisahkan data TNI dan PNS
        $tniData = $header->tukins()->where('tni_pns', 'TNI')->orderBy('nama_pegawai')->paginate(10, ['*'], 'tni_page');
        $pnsData = $header->tukins()->where('tni_pns', 'PNS')->orderBy('nama_pegawai')->paginate(10, ['*'], 'pns_page');

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

        return view('headers.edit', compact('header', 'satkers'));
    }

    public function update(Request $request, Header $header)
    {
        // Dynamic validation based on role
        $validator = Validator::make($request->all(), [
            'nama_header' => 'required|string|max:255',
            'kode_satker' => Auth::user()->role === 'admin'
                ? 'required|exists:satkers,kode_satker'
                : 'required|in:'.Auth::user()->kode_satker,
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $header->update([
            'nama_header' => $request->nama_header,
            'kode_satker' => Auth::user()->role === 'admin'
                ? $request->kode_satker
                : Auth::user()->kode_satker,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('headers.index')
            ->with('success', 'Header berhasil diperbarui');
    }

    public function destroy(Header $header)
    {
        // Delete related files
        Storage::disk('public')->delete([$header->file_tni_path, $header->file_pns_path]);
        
        $header->delete();

        return redirect()->route('headers.index')
            ->with('success', 'Header berhasil dihapus');
    }

    private function storeFileWithCustomName($file, $prefix)
    {
        try {
            $extension = $file->getClientOriginalExtension();
            $fileName = $prefix . '-' . time() . '_' . Str::random(10) . '.' . $extension;
            return $file->storeAs('tukin', $fileName, 'public');
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return null;
        }
    }
}
