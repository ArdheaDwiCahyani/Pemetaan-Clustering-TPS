<?php

namespace App\Imports;

use App\Models\Sampah;
use App\Models\Parameter;
use App\Models\Tps;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class SampahImport implements ToModel, WithHeadingRow
{
    protected $tahun;

    // Konstruktor untuk menerima tahun dari controller
    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function model(array $row)
    {

        $tps = Tps::where('namaTPS', $row['nama_tps'])->first();

        if (!$tps) {
            return null;
        }

        //membuat atau mendapatkan TPS berdasarkan nama dan kelurahan
        $sampah = Sampah::firstOrCreate([
            'tps_id' => $tps->id,
            'tahun' => $this->tahun,
            'volumeSampah' => $row['volume_sampah'],
        ]);

        return $sampah;
    }
}
