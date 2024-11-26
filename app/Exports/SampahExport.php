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
        $paramVolume = Parameter::where('namaParameter', 'Volume Sampah')->first();
        $paramRataRataJarak = Parameter::where('namaParameter', 'Rata-Rata Jarak')->first();

        if (!$paramVolume || !$paramRataRataJarak) {
            return collect();
        }

        return Sampah::where('tahun', $this->tahun)
            ->with(['tps', 'tps.parameter'])
            ->get()
            ->map(function ($sampah) use ($paramVolume, $paramRataRataJarak) {
                $volume = $sampah->tps->parameter
                    ->where('id', $paramVolume->id)
                    ->pluck('pivot.nilai_parameter')
                    ->first();

                $rataRataJarak = $sampah->tps->parameter
                    ->where('id', $paramRataRataJarak->id)
                    ->pluck('pivot.nilai_parameter')
                    ->first();

                return [
                    'nama_tps' => $sampah->tps->namaTPS ?? 'Tidak Diketahui',
                    'tahun' => $sampah->tahun,
                    'volume_sampah' => $volume ?? 'Tidak Tersedia',
                    'rata_rata_jarak' => $rataRataJarak ?? 'Tidak Tersedia',
                ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama TPS',
            'Tahun',
            'Volume Sampah (Ton)',
            'Rata-Rata Jarak (Km)',
        ];
    }
}
