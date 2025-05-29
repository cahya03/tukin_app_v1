<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Header;
use App\Models\Satker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HeaderController extends Controller
{
    public function index()
    {
        return view('create_header');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_header' => 'required|string|max:255',
            'deskripsi_header' => 'required|string|max:255',
            'kode_satker' => 'required|exists:satkers,kode_satker',
            'tanggal' => 'required|date',
            'file' => 'required|file|mimes:zip,xls,xlsx|max:2048'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan file
        $filePath = $request->file('file')->store('tukin', 'public');

        // Simpan data ke database
        Header::create([
            'nama_header' => $request->nama_header,
            'deskripsi_header' => $request->deskripsi_header,
            'kode_satker' => $request->kode_satker,
            'tanggal' => $request->tanggal,
            'file_path' => $filePath
        ]);

        return redirect()->back()->with('sukses', 'Header berhasil ditambahkan');
    }
}