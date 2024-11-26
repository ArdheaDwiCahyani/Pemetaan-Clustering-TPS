<?php

namespace App\Http\Controllers;

use App\Models\Jarak;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Sampah;
use App\Models\Tps;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // public function search(Request $request)
    // {
    //     $query = $request->input('query');
    //     $type = $request->get('type');

    //     switch ($type) {
    //         case 'kecamatan':
    //             $results = Kecamatan::where('namaKecamatan', 'LIKE', "%{$query}%")->get();
    //             break;
    //         case 'kelurahan':
    //             $results = Kelurahan::with('kecamatan') // Muat data kecamatan terkait
    //                 ->where('namaKelurahan', 'LIKE', "%{$query}%")
    //                 ->orWhereHas('kecamatan', function ($q) use ($query) {
    //                     $q->where('namaKecamatan', 'LIKE', "%{$query}%");
    //                 })
    //                 ->get();
    //             break;
    //         default:
    //             // Jika type tidak dikenali
    //             return response()->json(['message' => 'Tipe pencarian tidak valid'], 400);
    //     }

    //     // dd($results);

    //     return response()->json(['results' => $results]);
    // }


    public function search(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type');

        // Pencarian berdasarkan nama kelurahan atau kecamatan

        $results = '';

        if ($type == 'kelurahan') {
            # code...
            $results = Kelurahan::with('kecamatan') // Muat data kecamatan terkait
                ->where('namaKelurahan', 'LIKE', "%{$query}%")
                ->orWhereHas('kecamatan', function ($q) use ($query) {
                    $q->where('namaKecamatan', 'LIKE', "%{$query}%");
                })->get();
        } elseif ($type == 'kecamatan') {
            $results = Kecamatan::where('namaKecamatan', 'LIKE', "%{$query}%")->get();
        } elseif ($type == 'tps') {
            $results = Tps::with(['kelurahan', 'parameter'])
                ->where('namaTPS', 'LIKE', "%{$query}%")
                ->orWhereHas('kelurahan', function ($q) use ($query) {
                    $q->where('namaKelurahan', 'LIKE', "%{$query}%");
                })
                ->get()
                ->map(function ($row) {
                    $parameter = $row->parameter
                        ->where('namaParameter', 'Jarak ke TPA')
                        ->first();

                    $jarak = $parameter ? $parameter->pivot->nilai_parameter : 'N/A';

                    return [
                        'id' => $row->id,
                        'namaTPS' => $row->namaTPS,
                        'kelurahan' => $row->kelurahan ? $row->kelurahan->namaKelurahan : 'N/A',
                        'jarak' => $jarak,
                    ];
                });
        } else if ($type == 'jarak') {
            $results = Jarak::with(['tpsAsal', 'tpsTujuan'])
                ->whereHas('tpsAsal', function ($q) use ($query) {
                    $q->where('namaTPS', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('tpsTujuan', function ($q) use ($query) {
                    $q->where('namaTPS', 'LIKE', "%{$query}%");
                })
                ->get()
                ->map(function ($row) {
                    return [
                        'id' => $row->id,
                        'tpsAsal' => $row->tpsAsal ? $row->tpsAsal->namaTPS : 'N/A',
                        'tpsTujuan' => $row->tpsTujuan ? $row->tpsTujuan->namaTPS : 'N/A',
                        'jarak' => $row->jarak ?? 'N/A',
                    ];
                });
        } else if ($type == 'sampah') {
            $results = Sampah::with(['tps', 'tps.parameter'])
                ->whereHas('tps', function ($q) use ($query) {
                    $q->where('namaTPS', 'LIKE', "%{$query}%");
                })
                ->orWhere('tahun', 'LIKE', "%{$query}%")
                ->get()
                ->map(function ($row) {
                    $nilaiParameter = $row->tps->parameter
                        ->where('pivot.entity', 'sampah')
                        ->first()
                        ->pivot->nilai_parameter ?? '';

                    return [
                        'id' => $row->id,
                        'namaTPS' => $row->tps ? $row->tps->namaTPS : 'N/A',
                        'tahun' => $row->tahun,
                        'volume' => $nilaiParameter,
                        'jarakTPA' => $row->jarak_tpa ?? '',
                        'rataRataJarak' => $row->rata_rata_jarak ?? '',
                    ];
                });
        }

        return response()->json(['results' => $results]);
    }
}
