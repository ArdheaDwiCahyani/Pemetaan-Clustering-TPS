<?php

namespace App\Imports;

use App\Models\Jarak;
use App\Models\Tps;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JarakImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        // Pastikan TPS asal dan tujuan ada dalam tabel Tps
        $tpsAsal = Tps::where('namaTPS', $row['tps_asal'])->first();
        $tpsTujuan = Tps::where('namaTPS', $row['tps_tujuan'])->first();

        // Jika salah satu TPS tidak ditemukan, lewati baris ini
        if (!$tpsAsal || !$tpsTujuan) {
            Log::warning("TPS Asal atau Tujuan tidak ditemukan: " . $tpsAsal . " -> " . $tpsTujuan);
            return null;
        }

        $jarak = Jarak::firstOrCreate([
            'tps_asal_id' => $tpsAsal->id,
            'tps_tujuan_id' => $tpsTujuan->id,
            'jarak' => $row['jarak'],
        ]);

        return $jarak;
    }

    public function headingRow(): int
    {
        return 1; // Baris pertama sebagai header
    }
}
