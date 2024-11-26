<?php

namespace App\Exports;

use App\Models\Tps;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TpsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tps::with('kelurahan', 'parameter')->get()->map(function ($tps) {
            $jarakKeTpa = $tps->parameter->firstWhere('namaParameter', 'Jarak ke TPA');

            return [
                'nama_tps' => $tps->namaTPS,
                'nama_kelurahan' => $tps->kelurahan->namaKelurahan ?? 'Tidak Diketahui',
                'longitude' => $tps->longitude,
                'latitude' => $tps->latitude,
                'jarak_ke_tpa' => $jarakKeTpa->pivot->nilai_parameter ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama TPS',
            'Kelurahan',
            'Longitude',
            'Latitude',
            'Jarak ke TPA (Km)',
        ];
    }
}
