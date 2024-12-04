<?php

namespace App\Http\Controllers;

use App\Models\ClusteringResult;
use Illuminate\Http\Request;
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
        $results = ClusteringResult::with(['tps.kelurahan', 'sampah.parameter']) // Eager load 'tps', 'kelurahan', dan 'sampah'
            ->where('tahun', $tahun)
            ->get();

        $features = $results->map(function ($result) {
            // Ambil volume dari sampah yang terkait dengan tps
            $volume_sampah = $result->tps->parameterSampah()
                ->where('namaParameter', 'Volume Sampah')
                ->first()
                ->pivot->nilai_parameter ?? null;
            // $volume_sampah = $volume_sampah ? $volume_sampah->pivot->nilai_parameter : null;

            $jarak_ke_tpa = $result->tps->parameterTps()
                ->where('namaParameter', 'Jarak ke TPA')
                ->first();
            $jarak_ke_tpa = $jarak_ke_tpa ? $jarak_ke_tpa->pivot->nilai_parameter : null;

            $sampah = $result->tps->sampah->first();
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
                    'volume' => $volume_sampah,  // Volume sampah
                    'jarak' => $jarak_ke_tpa,  // Jarak ke TPA
                    'rata_rata_jarak' => $rata_rata_jarak,  // Rata-rata jarak
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
