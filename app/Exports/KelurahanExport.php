<?php

namespace App\Exports;

use App\Models\Kelurahan;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KelurahanExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Kelurahan::with('kecamatan')
            ->get()
            ->map(function ($kelurahan) {
                return [
                    'nama_kelurahan' => $kelurahan->namaKelurahan,
                    'nama_kecamatan' => $kelurahan->kecamatan->namaKecamatan ?? 'Tidak Diketahui',
                ];
        });
    }

    public function headings(): array
    {
        return ['Nama Kelurahan', 'Nama Kecamatan'];
    }
}
