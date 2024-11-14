<?php

namespace App\Imports;

use App\Models\Sampah;
use App\Models\Parameter;
use App\Models\Tps;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SampahImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Ambil ID parameter volume sampah (dengan nama parameter yang sudah diketahui)
        $paramVolume = Parameter::where('namaParameter', 'Volume Sampah')->first();
        $paramRataRataJarak = Parameter::where('namaParameter', 'Rata-Rata Jarak')->first();

        if (!$paramVolume || !$paramRataRataJarak) {
            return; // Keluar jika parameter 'Volume Sampah' tidak ditemukan
        }

        foreach ($rows as $row) {
            // Ambil data dari kolom Excel
            $namaTps = $row['nama_tps'];        // Kolom 'nama_tps'
            $tahun = $row['tahun'];             // Kolom 'tahun'
            $nilaiVolume = $row['volume_sampah']; // Kolom 'volume_sampah'

            // Cari TPS berdasarkan nama_tps
            $tps = Tps::where('namaTPS', $namaTps)->first();

            if ($tps) {
                // Buat data baru di tabel 'sampah'
                $sampah = Sampah::create([
                    'tps_id' => $tps->id, // Menggunakan id TPS yang ditemukan
                    'tahun' => $tahun,
                ]);

                // Simpan nilai volume sampah di tabel pivot 'tpsParameter'
                $tps->parameter()->attach($paramVolume->id, [
                    'nilai_parameter' => $nilaiVolume,
                    'entity' => 'sampah',
                ]);

                $rataRataJarak = $sampah->rata_rata_jarak;

                $tps->parameter()->attach($paramRataRataJarak->id, [
                    'nilai_parameter' => $rataRataJarak,
                    'entity' => 'sampah',
                ]);
            } else {
                // Jika tidak ditemukan TPS berdasarkan nama_tps, log atau tangani kesalahan sesuai kebutuhan
                // Misalnya, Anda bisa menambahkan log untuk data yang gagal diproses:
                Log::warning('TPS dengan nama ' . $namaTps . ' tidak ditemukan.');
            }
        }
    }
}
