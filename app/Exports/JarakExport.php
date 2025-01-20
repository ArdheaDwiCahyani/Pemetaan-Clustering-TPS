<?php

namespace App\Exports;

use App\Models\Jarak;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JarakExport implements FromCollection, WithHeadings, ShouldAutoSize // mengikuti panjang data yang diekspor
{
    public function collection()
    {
        return Jarak::with('tpsAsal', 'tpsTujuan')->get()->map(function ($jarak) {
            return [
                'tps_asal' => $jarak->tpsAsal->namaTPS ?? 'Tidak Diketahui',
                'tps_tujuan' => $jarak->tpsTujuan->namaTPS ?? 'Tidak Diketahui',
                'jarak' => $jarak->jarak,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'TPS Asal',
            'TPS Tujuan',
            'Jarak (Km)',
        ];
    }
}
