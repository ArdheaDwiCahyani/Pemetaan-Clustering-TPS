<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelurahanImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        // Mencari ID kecamatan berdasarkan nama kecamatan yang diinput di Excel
        $kecamatan = Kecamatan::where('namaKecamatan', $row['nama_kecamatan'])->first();

        if(!$kecamatan) {
            return null;
        }

        $kelurahan = Kelurahan::firstOrCreate([
            'namaKelurahan' => $row['nama_kelurahan'],
            'kecamatan_id' => $kecamatan->id,
        ]);

        return $kelurahan;
    }

    public function headingRow(): int
    {
        return 1; // Baris pertama sebagai header
    }
}
