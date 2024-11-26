<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClusterExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $dataForExport;

    public function __construct($dataForExport)
    {
        $this->dataForExport = $dataForExport;
    }

    public function collection()
    {
        return $this->dataForExport;
    }

    public function headings(): array
    {
        return [
            'Nama TPS',
            'Volume Sampah (Ton)',
            'Jarak ke TPA',
            'Rata-Rata Jarak',
            'Cluster',
            'Prioritas',
        ];
    }
}
