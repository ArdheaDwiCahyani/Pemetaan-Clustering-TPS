<?php

namespace App\Http\Controllers;

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
    public function addTahun(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric|digits:4',
        ]);

        $tahunList = session('tahun_list', []);

        if (!in_array($request->tahun, $tahunList)) {
            session()->push('tahun_list', $request->tahun);
        }

        return redirect()->route('sampah');
    }

    public function removeTahun(Request $request)
    {
        // Ambil tahun yang dipilih dari request
        $tahun = $request->input('tahun');

        // Pastikan tahun yang dipilih tidak kosong dan ada di session
        if ($tahun && session()->has('tahun_list')) {
            // Ambil daftar tahun yang ada di session
            $tahunList = session('tahun_list');

            // Jika tahun ditemukan, hapus dari array
            if (($key = array_search($tahun, $tahunList)) !== false) {
                unset($tahunList[$key]);
                session()->put('tahun_list', array_values($tahunList));
            }
        }

        // Redirect kembali ke halaman daftar sampah atau halaman yang diinginkan
        return redirect()->route('sampah');
    }

    public function index(Request $request)
    {
        $tahunDatabase = Sampah::distinct()->pluck('tahun')->toArray();

        // Ambil tahun dari session (jika ada)
        $tahunSession = session('tahun_list', []);

        // Gabungkan tahun dari database dan session, lalu hilangkan duplikasi
        $tahunList = array_unique(array_merge($tahunDatabase, $tahunSession));

        // Ambil filter tahun dan jumlah item per halaman
        $tahun = $request->input('tahun');
        $perPage = $request->input('per_page', 4);

        $isDataAvailable = false;
        if ($tahun) {
            $isDataAvailable = Sampah::where('tahun', $tahun)->exists();
        }

        if ($tahun) {
            $sampah = Sampah::where('tahun', $tahun)->paginate($perPage);
        } else {
            $sampah = Sampah::paginate($perPage);
        }

        // Pass data ke view
        return view('sampah.index', compact('tahunList', 'sampah', 'tahun', 'isDataAvailable'));
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
            'tahun' => 'required|numeric|digits:4',
            'volume_sampah' => 'required|array',
            'volume.*' => 'numeric',
        ]);

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

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);
        // Import file
        $import = new SampahImport($tahun); // Kirimkan tahun ke class import

        Excel::import($import, $request->file('file'));

        return redirect()->route('sampah')->with('success', 'Data berhasil diimpor!');
    }
}
