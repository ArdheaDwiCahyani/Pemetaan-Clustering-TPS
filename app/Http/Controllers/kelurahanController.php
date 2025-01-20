<?php

namespace App\Http\Controllers;

use App\Exports\KelurahanExport;
use App\Imports\KelurahanImport;
use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class kelurahanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data kecamatan untuk dropdown filter
        $kecamatan = Kecamatan::all();

        return view('kelurahan.index', compact('kecamatan'));
    }

    public function allKelurahan()
    {
        // Ambil semua data Kelurahan
        $allKelurahan = Kelurahan::all();

        // Ubah data untuk mengganti kecamatan_id dengan nama kecamatan
        $response = $allKelurahan->map(function ($kelurahan) {
            $kecamatan = Kecamatan::find($kelurahan->kecamatan_id);
            return [
                'id' => $kelurahan->id,
                'namaKelurahan' => $kelurahan->namaKelurahan,
                'kecamatan' => $kecamatan ? $kecamatan->namaKecamatan : 'Tidak Diketahui', // Handle jika kecamatan tidak ditemukan
            ];
        });

        // Return dalam format JSON
        return response()->json($response);
    }

    public function tambah()
    {
        $kecamatan = Kecamatan::where('namaKecamatan', '!=', '-- Pilih Kecamatan --')->get();
        return view('kelurahan.form', compact('kecamatan'));
    }

    public function simpan(Request $request)
    {
        $validatedData = $request->validate([
            'namaKelurahan' => 'required|string|min:5|max:100',
            'kecamatan_id' => 'required'
        ]);

        Kelurahan::create([
            'namaKelurahan' => $validatedData['namaKelurahan'],
            'kecamatan_id' => $validatedData['kecamatan_id']
        ]);

        return redirect()->route('kelurahan');
    }

    public function edit($id)
    {
        $kelurahan = Kelurahan::find($id);
        $kecamatan = Kecamatan::all();

        return view('kelurahan.formEdit', compact('kelurahan', 'kecamatan'));
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'namaKelurahan' => 'required|string|min:5|max:100',
            'kecamatan_id' => 'required'
        ]);

        $kelurahan = Kelurahan::find($id);
        $kelurahan->update([
            'namaKelurahan' => $validatedData['namaKelurahan'],
            'kecamatan_id' => $validatedData['kecamatan_id'],
        ]);

        return redirect()->route('kelurahan');
    }

    public function hapus($id)
    {
        $kelurahan = Kelurahan::find($id);
        $kelurahan->delete(); // Menghapus kelurahaN
    }

    //fungsi import
    public function importForm()
    {
        return view('kelurahan.import');
    }

    //proses file import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        //ambil file yg diupload
        $file = $request->file('file');

        //mengimpor data ke database
        Excel::import(new KelurahanImport, $file);

        return redirect()->route('kelurahan');
    }

    public function export()
    {
        return Excel::download(new KelurahanExport, 'data-kelurahan.xlsx');
    }
}
