<?php

namespace App\Exports;

use App\Models\Tps;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TpsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Tps::with('kelurahan')->get()->map(function ($tps) {

            return [
                'nama_tps' => $tps->namaTPS,
                'nama_kelurahan' => $tps->kelurahan->namaKelurahan ?? 'Tidak Diketahui',
                'jarak_ke_tpa' => $tps->jarakTPA,
                'longitude' => $tps->longitude,
                'latitude' => $tps->latitude,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama TPS',
            'Kelurahan',
            'Jarak ke TPA (Km)',
            'Longitude',
            'Latitude',
        ];
    }
}
