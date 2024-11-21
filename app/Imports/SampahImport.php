<?php

namespace App\Imports;

use App\Models\Sampah;
use App\Models\Parameter;
use App\Models\Tps;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SampahImport implements ToCollection, WithHeadingRow
{
    protected $tahun;

    // Konstruktor untuk menerima tahun dari controller
    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection(Collection $rows)
    {
        // Ambil ID parameter volume sampah dan rata-rata jarak (parameter yang sudah diketahui)
        $paramVolume = Parameter::where('namaParameter', 'Volume Sampah')->first();
        $paramRataRataJarak = Parameter::where('namaParameter', 'Rata-Rata Jarak')->first();

        // Jika salah satu parameter tidak ditemukan, hentikan eksekusi
        if (!$paramVolume || !$paramRataRataJarak) {
            Log::warning('Parameter "Volume Sampah" atau "Rata-Rata Jarak" tidak ditemukan.');
            return; // Keluar jika salah satu parameter tidak ditemukan
        }

        // Proses setiap baris data dari Excel
        foreach ($rows as $row) {
            // Ambil data dari kolom Excel
            $namaTps = $row['nama_tps'];        // Kolom 'nama_tps'
            $tahun = $this->tahun;              // Menggunakan tahun yang diteruskan dari konstruktor
            $nilaiVolume = $row['volume_sampah']; // Kolom 'volume_sampah'

            // Cari TPS berdasarkan nama_tps
            $tps = Tps::where('namaTPS', $namaTps)->first();

            if ($tps) {
                // Buat data baru di tabel 'sampah' dengan menggunakan 'tahun' yang diteruskan
                $sampah = Sampah::create([
                    'tps_id' => $tps->id,  // Menggunakan id TPS yang ditemukan
                    'tahun' => $tahun,      // Menggunakan tahun yang diteruskan dari konstruktor
                ]);

                // Simpan nilai volume sampah di tabel pivot 'tpsParameter'
                $tps->parameter()->attach($paramVolume->id, [
                    'nilai_parameter' => $nilaiVolume,
                    'entity' => 'sampah',
                ]);

                // Ambil nilai rata-rata jarak dari objek 'sampah' yang baru dibuat
                $rataRataJarak = $sampah->rata_rata_jarak;

                // Simpan nilai rata-rata jarak di tabel pivot 'tpsParameter'
                $tps->parameter()->attach($paramRataRataJarak->id, [
                    'nilai_parameter' => $rataRataJarak,
                    'entity' => 'sampah',
                ]);
            } else {
                // Jika tidak ditemukan TPS berdasarkan nama_tps, log atau tangani kesalahan sesuai kebutuhan
                Log::warning('TPS dengan nama ' . $namaTps . ' tidak ditemukan.');
            }
        }
    }
}
