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
                'volume_sampah' => $data->volumeSampah,
                'jarak_tpa' => $data->tps->jarakTPA ?? null,
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
        $tps = Tps::all();
        $tahunList = Sampah::select('tahun')->distinct()->get();

        // Pass data ke view, termasuk tahun yang dipilih
        return view('sampah.form', compact('tps', 'tahun'));
    }

    public function simpan(Request $request)
    {
        $validatedData = $request->validate([
            'tps_id' => 'required|exists:tps,id',
            'tahun' => 'required|digits:4|numeric',
            'volumeSampah' => 'required|numeric',
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
            'volumeSampah' => $validatedData['volumeSampah'],
        ]);

        $tps = Tps::find($validatedData['tps_id']);

        return redirect()->route('sampah');
    }

    public function edit($id)
    {
        $sampah = Sampah::find($id);
        $tps = Tps::all();

        return view('sampah.formEdit', compact('sampah', 'tps'));
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'tps_id' => 'required|exists:tps,id',
            'tahun' => 'required|numeric|digits:4',
            'volumeSampah' => 'required|numeric',
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
                'volumeSampah' => $validatedData['volumeSampah'],
            ]);
        }

        return redirect()->route('sampah');
    }

    public function hapus($id)
    {
        $sampah = Sampah::find($id);

        if ($sampah) {
            $sampah->delete(); // Menghapus sampah
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

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
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
