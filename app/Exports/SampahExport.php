<?php

namespace App\Exports;

use App\Models\Parameter;
use App\Models\Sampah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SampahExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $tahun;
    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return Sampah::where('tahun', $this->tahun)
            ->with('tps')
            ->get()
            ->map(function ($sampah) {
                return [
                    'nama_tps' => $sampah->tps->namaTPS ?? 'Tidak Diketahui',
                    'tahun' => $sampah->tahun,
                    'volume_sampah' => $sampah->volumeSampah ?? 'Tidak Tersedia',
                    'jarak_ke_tpa' => $sampah->tps->jarakTPA ?? 'Tidak Tersedia',
                    'rata_rata_jarak' => $sampah->rataRataJarak ?? 'Tidak Tersedia',
                ];
        });

    }

    public function headings(): array
    {
        return [
            'Nama TPS',
            'Tahun',
            'Volume Sampah (Ton)',
            'Jarak ke TPA (Km)',
            'Rata-Rata Jarak (Km)',
        ];
    }
}
