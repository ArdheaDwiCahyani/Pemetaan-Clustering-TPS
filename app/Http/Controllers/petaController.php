<?php

namespace App\Http\Controllers;

use App\Models\ClusteringResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetaController extends Controller
{
    public function indexPeta()
    {
        // Mengambil semua tahun untuk dropdown
        $tahun = ClusteringResult::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Menentukan tahun yang dipilih, default ke tahun terbaru
        $selectedYear = $tahun[0] ?? null;

        return view('peta.map', compact('tahun', 'selectedYear'));
    }

    public function geojsonData($tahun)
    {
        $results = ClusteringResult::with([
            'tps.kelurahan',           // Relasi ke kelurahan
            'tps.parameterTps',        // Parameter TPS
            'tps.sampah.parameterSampah', // Parameter Sampah melalui TPS
        ])->where('tahun', $tahun)->get();

        $features = $results->map(function ($result) {
            $sampah = $result->tps->sampah->first(); // Ambil data sampah terkait TPS
            $volume_sampah = $sampah
                ? $sampah->parameterSampah()
                ->where('namaParameter', 'Volume Sampah')
                ->first()->pivot->nilai_parameter ?? null
                : null;

            $jarak_ke_tpa = $result->tps->parameterTps()
                ->where('namaParameter', 'Jarak ke TPA')
                ->first()->pivot->nilai_parameter ?? null;

            $rata_rata_jarak = $sampah ? $sampah->rata_rata_jarak : null;

            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$result->tps->longitude, $result->tps->latitude],
                ],
                'properties' => [
                    'namaTPS' => $result->tps->namaTPS,
                    'kelurahan' => $result->tps->kelurahan->namaKelurahan ?? null,
                    'volume' => $volume_sampah,
                    'jarak' => $jarak_ke_tpa,
                    'rata_rata_jarak' => $rata_rata_jarak,
                    'cluster' => $result->cluster,
                    'prioritas' => $result->prioritas,
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    public function showMap(Request $request)
    {
        $selectedYear = $request->input('tahun', date('Y'));

        // Ambil daftar tahun dari tabel clustering_results
        $tahun = ClusteringResult::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();

        // Menampilkan peta dengan data tahun yang dipilih
        return view('peta.map', compact('tahun', 'selectedYear'));
    }
}
