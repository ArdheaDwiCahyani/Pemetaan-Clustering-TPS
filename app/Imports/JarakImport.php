<?php

namespace App\Imports;

use App\Models\Jarak;
use App\Models\Tps;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JarakImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Pastikan TPS asal dan tujuan ada dalam tabel Tps
        $tpsAsal = Tps::where('namaTPS', $row['tps_asal'])->first();
        $tpsTujuan = Tps::where('namaTPS', $row['tps_tujuan'])->first();

        // Jika salah satu TPS tidak ditemukan, lewati baris ini
        if (!$tpsAsal || !$tpsTujuan) {
            return null;
        }

        // Membuat dan mengembalikan instance model Jarak
        return new Jarak([
            'tps_asal_id' => $tpsAsal->id,
            'tps_tujuan_id' => $tpsTujuan->id,
            'jarak' => $row['jarak'],
        ]);
    }

    public function headingRow(): int
    {
        return 1; // Baris pertama sebagai header
    }
}
