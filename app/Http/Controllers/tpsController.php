<?php

namespace App\Http\Controllers;

use App\Exports\TpsExport;
use App\Imports\TpsImport;
use App\Models\Kelurahan;
use App\Models\Parameter;
use App\Models\Tps;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class tpsController extends Controller
{
    public function index(Request $request)
    {

        $tps = Tps::with(['kelurahan']);
        $kelurahan = Kelurahan::all();

        return view('tps.index', compact('tps', 'kelurahan'));
    }

    public function allTps()
    {
        $allTps = Tps::all();

        $response = $allTps->map(function ($tps) {
            $kelurahan = Kelurahan::find($tps->kelurahans_id);
            return [
                'id' => $tps->id,
                'nama_tps' => $tps->namaTPS,
                'nama_kelurahan' => $kelurahan ? $kelurahan->namaKelurahan : 'Tidak Diketahui',
                'jarak_tpa' => $tps->jarakTPA,
            ];
        });

        return response()->json($response);
    }

    public function tambah()
    {
        $kelurahan = Kelurahan::all();

        return view('tps.form', compact('kelurahan'));
    }

    public function simpan(Request $request)
    {
        //validasi input
        $validatedData = $request->validate([
            'namaTPS' => 'required|string|max:100',
            'kelurahans_id' => 'required|exists:kelurahans,id',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'jarakTPA' => 'required|numeric',
        ]);

        // Pastikan koordinat dibatasi dengan presisi tertentu sebelum disimpan
        $latitude = round($validatedData['latitude'], 8);  // Menggunakan 8 angka desimal
        $longitude = round($validatedData['longitude'], 8);  // Menggunakan 8 angka desimal

        $tps = Tps::create([
            'namaTPS' => $validatedData['namaTPS'],
            'kelurahans_id' => $validatedData['kelurahans_id'],
            'latitude' => $latitude,
            'longitude' => $longitude,
            'jarakTPA' => $validatedData['jarakTPA'],
        ]);

        return redirect()->route('tps');
    }

    public function edit($id)
    {
        $tps = Tps::with(['kelurahan'])->find($id);
        $kelurahan = Kelurahan::all();

        return view('tps.formEdit', compact('tps', 'kelurahan'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'namaTPS' => 'required|string|max:100',
            'kelurahans_id' => 'required|exists:kelurahans,id',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'jarakTPA' => 'required|numeric',
        ]);

        $tps = Tps::find($id);
        $tps->update([
            'namaTPS' => $validatedData['namaTPS'],
            'kelurahans_id' => $validatedData['kelurahans_id'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'jarakTPA' => $validatedData['jarakTPA'],
        ]);

        return redirect()->route('tps');
    }

    public function hapus($id)
    {
        $tps = Tps::find($id);
        $tps->delete();

        if ($tps) {
            return response()->json(['message' => 'Item deleted successfully.']);
        } else {
            return response()->json(['message' => 'Item not found.'], 404);
        }
    }

    //fungsi import
    public function importForm()
    {
        return view('tps.import');
    }

    //proses file import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        //ambil file yg diupload
        $file = $request->file('file');

        Excel::import(new TpsImport, $file);

        return redirect()->route('tps');
    }

    public function export()
    {
        return Excel::download(new TpsExport, 'data-TPS.xlsx');
    }
}
