<?php

namespace App\Http\Controllers;

use App\Exports\SampahExport;
use App\Imports\SampahImport;
use App\Models\Parameter;
use App\Models\Sampah;
use App\Models\Tps;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class sampahController extends Controller
{

    public function index(Request $request)
    {
        return view('sampah.index');
    }

    //semua data sampah
    public function allSampah()
    {
        $sampah = Sampah::all();

        $sampahArray = $sampah->map(function ($data) {
            return [
                'id' => $data->id ?? null,
                'nama_tps' => $data->tps->namaTPS ?? null,
                'tahun' => $data->tahun ?? null,
                'volume_sampah' => $data->tps->parameter
                    ->where('pivot.entity', 'sampah')
                    ->first()
                    ->pivot
                    ->nilai_parameter ?? null,
                'jarak_tpa' => $data->jarak_tpa ?? null,
                'rata_rata_jarak' => $data->rata_rata_jarak ?? null,
            ];
        });

        return response()->json($sampahArray);
    }

    public function tambah(Request $request)
    {
        // Ambil tahun dari request (dari URL)
        $tahun = $request->input('tahun');

        // Ambil data lainnya
        $tps = Tps::with('parameter')->get();
        $parameter = Parameter::all();
        $tahunList = Sampah::select('tahun')->distinct()->get();

        // Pass data ke view, termasuk tahun yang dipilih
        return view('sampah.form', compact('tps', 'parameter', 'tahunList', 'tahun'));
    }

    public function simpan(Request $request)
    {
        $validatedData = $request->validate([
            'tps_id' => 'required|exists:tps,id',
            'tahun' => 'required|digits:4|numeric',
            'volume_sampah' => 'required|array',
            'volume.*' => 'numeric',
        ]);

        // Cek apakah kombinasi tps_id dan tahun sudah ada
        $existingRecord = Sampah::where('tps_id', $request->tps_id)
            ->where('tahun', $request->tahun)
            ->first();

        if ($existingRecord) {
            // Jika ada, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->withErrors([
                'tps_tahun_exists' => 'Data untuk TPS dan tahun ini sudah ada !',
            ]);
        }

        $sampah = Sampah::create([
            'tps_id' => $validatedData['tps_id'],
            'tahun' => $validatedData['tahun'],
        ]);

        //mendapatkan parameter dari tabel parameter
        $param_volume = Parameter::where('namaParameter', 'Volume Sampah')->first();
        $tps = Tps::find($validatedData['tps_id']);

        if ($param_volume) {
            //simpan nilai volume sampah ke tabel pivot tpsParameter
            foreach ($validatedData['volume_sampah'] as $params_id => $nilai_volume) {
                $tps->parameter()->attach($params_id, [
                    'nilai_parameter' => $nilai_volume,
                    'entity' => 'sampah', //menentukan entity sebagai sampah
                ]);
            }
        }

        $rata_rata_jarak = $sampah->rata_rata_jarak; //ambil nilai rata-rata jarak
        $param_rata_jarak = Parameter::where('namaParameter', 'Rata-Rata Jarak')->first(); //ambil parameter u/ ratajarak

        if ($param_rata_jarak) {
            $tps->parameter()->syncWithoutDetaching([
                $param_rata_jarak->id => [
                    'nilai_parameter' => $rata_rata_jarak,
                    'entity' => 'sampah',
                ]
            ]);
        }

        return redirect()->route('sampah');
    }

    public function edit($id)
    {
        $sampah = Sampah::with(['tps.parameter' => function ($query) {
            $query->wherePivot('entity', 'sampah');
        }])->find($id);

        $tps = Tps::all();
        $parameter = Parameter::all();

        return view('sampah.formEdit', compact('sampah', 'tps', 'parameter'));
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'tps_id' => 'required|exists:tps,id',
            'tahun' => 'required|numeric|digits:4',
            'volume_sampah' => 'required|array',
            'volume_sampah.*' => 'numeric', // Validasi nilai volume sampah
        ]);

        $sampah = Sampah::find($id);

        // Cek apakah kombinasi tps_id dan tahun sudah ada
        $existingRecord = Sampah::where('tps_id', $request->tps_id)
            ->where('tahun', $request->tahun)
            ->first();

        if ($existingRecord) {
            // Jika ada, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->withErrors([
                'tps_tahun_exists' => 'Data untuk TPS dan tahun ini sudah ada !',
            ]);
        }

        if ($sampah) {
            $sampah->update([
                'tps_id' => $validatedData['tps_id'],
                'tahun' => $validatedData['tahun'],
            ]);

            $param_volume = Parameter::where('namaParameter', 'Volume Sampah')->first();

            //jika param volume ditemukan
            if ($param_volume) {
                // Mengupdate nilai volume sampah pada tabel pivot dengan entity 'sampah'
                foreach ($validatedData['volume_sampah'] as $params_id => $nilai_volume) {
                    $sampah->tps->parameter()->updateExistingPivot($params_id, [
                        'nilai_parameter' => $nilai_volume,
                        'entity' => 'sampah', // Tentukan entity sebagai 'sampah'
                    ]);
                }
            }

            $rata_rata_jarak = $sampah->rata_rata_jarak;
            $param_rata_jarak = Parameter::where('namaParameter', 'Rata-Rata Jarak')->first();
            if ($param_rata_jarak) {
                $sampah->tps->parameter()->syncWithoutDetaching($param_rata_jarak->id, [
                    'nilai_parameter' => $rata_rata_jarak,
                    'entity' => 'sampah',
                ]);
            }
        }

        return redirect()->route('sampah');
    }

    public function hapus($id)
    {
        $sampah = Sampah::find($id);

        if ($sampah) {
            $sampah->delete(); // Menghapus sampah
            // Menghapus entri pivot terkait 'sampah' dari tabel tpsParameter
            $sampah->tps->parameter()->wherePivot('entity', 'sampah')->detach();
            return response()->json(['message' => 'Item deleted successfully.']);
        } else {
            return response()->json(['message' => 'Item not found.'], 404);
        }
    }

    //fungsi import
    public function importForm()
    {
        return view('sampah.import');
    }

    //proses file import
    public function import(Request $request)
    {
        $tahun = $request->input('tahun');
        // dd($tahun);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            // 'tahun' => 'required|digits:4',
        ]);
        // Import file
        $import = new SampahImport($tahun); // Kirimkan tahun ke class import

        Excel::import($import, $request->file('file'));

        return redirect()->route('sampah')->with('success', 'Data berhasil diimpor!');
    }

    public function export(Request $request)
    {
        $tahun = $request->input('tahun');

        return Excel::download(new SampahExport($tahun), 'data-sampah-' . $tahun . '.xlsx');
    }
}
