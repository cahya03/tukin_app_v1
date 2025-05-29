<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\TukinImport;
use App\Models\Tukin;
use Maatwebsite\Excel\Facades\Excel;

class TukinController extends Controller
{
    public function index()
    {
        $tukin = Tukin::all();
        return view('tukin', ['tukin' => $tukin]);
    }

    public function create_tukin()
    {
        $tukin = Tukin::all();
        return view('create_tukin', ['tukin' => $tukin]);
    }

    public function import_excel_tni(Request $request)
    {
        // Debug untuk lihat MIME Type sebenarnya
        if ($request->hasFile('file')) {
            $file = $request->file('file');
        }

        //validasi file
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,zip',
        ]);

        // menangkap file excel
        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = rand() . $file->getClientOriginalName();

        // upload ke folder file_tukin di dalam folder public
        $file->move('file_tukin', $nama_file);

        // import data
        Excel::import(new TukinImport('TNI'), public_path('/file_tukin/' . $nama_file));


        // alihkan halaman kembali
        return redirect()->back()->with('success', 'File berhasil diimpor!');
    }
}
