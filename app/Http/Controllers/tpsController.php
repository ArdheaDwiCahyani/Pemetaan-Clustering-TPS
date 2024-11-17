<?php

namespace App\Http\Controllers;

use App\Imports\TpsImport;
use App\Models\Kelurahan;
use App\Models\Parameter;
use App\Models\Tps;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class tpsController extends Controller
{
    public function index()
    {

        $tps = Tps::with(['parameter', 'kelurahan'])->get();
        $parameter = Parameter::all();
        $kelurahan = Kelurahan::all();

        return view('tps.index', compact('tps', 'parameter', 'kelurahan'));
    }

    public function tambah()
    {
        $kelurahan = Kelurahan::all();
        $parameter = Parameter::all();

        return view('tps.form', compact('kelurahan', 'parameter'));
    }

    public function simpan(Request $request)
    {
        //validasi input
        $validatedData = $request->validate([
            'namaTPS' => 'required|string|max:100',
            'kelurahans_id' => 'required|exists:kelurahans,id',
            'nilai_parameter' => 'required|array',
            'nilai_parameter.*' => 'numeric',
            'params_id' => 'required|array',
            'params_id.*' => 'exists:params,id',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
        ]);

        
        // Pastikan koordinat dibatasi dengan presisi tertentu sebelum disimpan
        $latitude = round($validatedData['latitude'], 8);  // Menggunakan 8 angka desimal
        $longitude = round($validatedData['longitude'], 8);  // Menggunakan 8 angka desimal
        

        $tps = Tps::create([
            'namaTPS' => $validatedData['namaTPS'],
            'kelurahans_id' => $validatedData['kelurahans_id'],
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
        

        //menyimpan parameter dengan nilai ke tabel pivot (tpsParameter)
        $parameterData = [];
        foreach ($validatedData['params_id'] as $index => $params_id) {
            //ambil data berdasarkan id
            $parameter = Parameter::find($params_id);

            //hanya simpan nilai jika parameter adalah 'jarak ke tpa'
            if ($parameter) {
                //simpan nilai ke pivot untuk setiap parameter, termasuk "Jarak ke TPA"
                $parameterData[$params_id] = [
                    'nilai_parameter' => $validatedData['nilai_parameter'][$index],
                    'entity' => 'tps'
                ];
            }
        }

        if (!empty($parameterData)) {
            $tps->parameter()->attach($parameterData);
        }

        return redirect()->route('tps');
    }

    public function edit($id)
    {
        $tps = Tps::with(['parameter', 'kelurahan'])->find($id);
        $kelurahan = Kelurahan::all();
        $parameter = Parameter::all();

        return view('tps.formEdit', compact('tps', 'kelurahan', 'parameter'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'namaTPS' => 'required|string|max:100',
            'kelurahans_id' => 'required|exists:kelurahans,id',
            'nilai_parameter' => 'required|array',
            'nilai_parameter.*' => 'numeric',
            'params_id' => 'required|array',
            'params_id.*' => 'exists:params,id',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
        ]);

        $tps = Tps::find($id);
        $tps->update([
            'namaTPS' => $validatedData['namaTPS'],
            'kelurahans_id' => $validatedData['kelurahans_id'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
        ]);

        foreach ($validatedData['params_id'] as $index => $params_id) {
            // Ambil parameter berdasarkan id
            $parameter = Parameter::find($params_id);

            // Jika parameter ditemukan, lakukan pembaruan
            if ($parameter) {
                // Tentukan entity sebagai 'tps' jika ini adalah parameter untuk Tps
                if ($parameter->namaParameter == 'Jarak ke TPA') {
                    $tps->parameter()->updateExistingPivot($params_id, [
                        'nilai_parameter' => $validatedData['nilai_parameter'][$index],
                        'entity' => 'tps', // Entity 'tps' untuk jarak ke TPA
                    ]);
                }
                // Jika parameter adalah Volume Sampah, lakukan pembaruan terpisah untuk sampah
                if ($parameter->namaParameter == 'Volume Sampah') {
                    $tps->parameter()->updateExistingPivot($params_id, [
                        'nilai_parameter' => $validatedData['nilai_parameter'][$index],
                        'entity' => 'sampah', // Entity 'tps' untuk volume sampah
                    ]);
                }
            }
        }

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
}
