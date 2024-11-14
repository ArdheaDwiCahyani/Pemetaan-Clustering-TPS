<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelurahanImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * Map each row in the spreadsheet to the Kelurahan model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Mencari ID kecamatan berdasarkan nama kecamatan yang diinput di Excel
        $kecamatan = Kecamatan::where('namaKecamatan', $row['nama_kecamatan'])->first();

        if(!$kecamatan) {
            return null;
        }

        return new Kelurahan([
            'namaKelurahan' => $row['nama_kelurahan'],
            'kecamatan_id' => $kecamatan->id,
        ]);
    }

    public function headingRow(): int
    {
        return 1; // Baris pertama sebagai header
    }
}
