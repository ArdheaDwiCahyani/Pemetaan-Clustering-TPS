<?php

namespace App\Imports;

use App\Models\Kelurahan;
use App\Models\Parameter;
use App\Models\Tps;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TpsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
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
        ]);

        //mendapatkan parameter jarak ke tpa
        $parameter = Parameter::where('namaParameter', 'Jarak ke TPA')->first();

        if ($parameter) {
            //menyimpan nilai jarak ke pivot table 'tpsParameter'
            $tps->parameter()->attach($parameter->id, [
                'nilai_parameter' => $row['jarak_ke_tpa'],
                'entity' =>'tps',
            ]);
        }
        return $tps;
    }
}
