<?php

namespace App\Http\Controllers;

use App\Imports\KelurahanImport;
use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class kelurahanController extends Controller
{
    public function index()
    {
        $kelurahan = Kelurahan::with('kecamatan')->get();
        return view('kelurahan.index', compact('kelurahan'));
    }

    public function tambah()
    {
        $kecamatan = Kecamatan::get();
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

    public function hapus($id) {
        $kelurahan = Kelurahan::find($id);

        if ($kelurahan) {
            $kelurahan->delete(); // Menghapus kelurahan
            return response()->json(['message' => 'Item deleted successfully.']);
        } else {
            return response()->json(['message' => 'Item not found.'], 404);
        }
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

        Excel::import(new KelurahanImport, $file);

        return redirect()->route('kelurahan');
    }
}
