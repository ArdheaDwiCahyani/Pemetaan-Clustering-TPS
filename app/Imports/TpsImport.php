<?php

namespace App\Imports;

use App\Models\Kelurahan;
use App\Models\Parameter;
use App\Models\Tps;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TpsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        $kelurahan = Kelurahan::where('namaKelurahan', $row['nama_kelurahan'])->first();

        if (!$kelurahan) {
            return null;
        }

        //membuat atau mendapatkan TPS berdasarkan nama dan kelurahan
        $tps = Tps::firstOrCreate([
            'namaTPS' => $row['nama_tps'],
            'kelurahans_id' => $kelurahan->id,
            'longitude' => $row['longitude'],
            'latitude' => $row['latitude'],
            'jarakTPA' => $row['jarak_ke_tpa'],
        ]);

        return $tps;
    }

    public function headingRow(): int
    {
        return 1; // Baris pertama sebagai header
    }
}
