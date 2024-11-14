<?php

namespace App\Http\Controllers;
use App\Models\Tps;

class ProsesController extends Controller
{
    public function normalizeSampahData()
    {
        $sampahData = Tps::with([
            'parameterSampah' => function ($query) {
                $query->whereIn('namaParameter', ['Volume Sampah', 'Rata-Rata Jarak'])->withPivot('nilai_parameter');
            },
            'parameterTps' => function ($query) {
                $query->where('namaParameter', 'Jarak ke TPA')->withPivot('nilai_parameter');
            }
        ])->get();

        $volumeData = [];
        $jarakData = [];
        $rataRataData = [];

        // Menyimpan data asli untuk setiap TPS
        $originalData = [];

        foreach ($sampahData as $tps) {
            $originalEntry = [
                'tps_id' => $tps->id,
                'namaTPS' => $tps->namaTPS,
                'volume' => null,
                'jarak' => null,
                'rata_rata_jarak' => null,
            ];

            foreach ($tps->parameterSampah as $parameter) {
                if ($parameter->namaParameter == 'Volume Sampah') {
                    $volumeData[] = $parameter->pivot->nilai_parameter;
                    $originalEntry['volume'] = $parameter->pivot->nilai_parameter;
                } elseif ($parameter->namaParameter == 'Rata-Rata Jarak') {
                    $rataRataData[] = $parameter->pivot->nilai_parameter;
                    $originalEntry['rata_rata_jarak'] = $parameter->pivot->nilai_parameter;
                }
            }

            if ($tps->parameterTps->isNotEmpty()) {
                $jarakData[] = $tps->parameterTps->first()->pivot->nilai_parameter;
                $originalEntry['jarak'] = $tps->parameterTps->first()->pivot->nilai_parameter;
            }

            $originalData[] = $originalEntry;
        }

        $normalize = function ($data) {
            $min = !empty($data) ? min($data) : 0;
            $max = !empty($data) ? max($data) : 1;
            return array_map(fn($value) => ($max - $min) ? ($value - $min) / ($max - $min) : 0, $data);
        };

        $normalizedData = [
            'volume' => $normalize($volumeData),
            'jarak' => $normalize($jarakData),
            'rataRata' => $normalize($rataRataData),
        ];

        // Menggabungkan data asli dengan data normalisasi
        $normalizedDataWithOriginal = collect($originalData)->map(function ($original, $index) use ($normalizedData) {
            return array_merge($original, [
                'normalized_volume' => $normalizedData['volume'][$index] ?? null,
                'normalized_jarak' => $normalizedData['jarak'][$index] ?? null,
                'normalized_rata_rata_jarak' => $normalizedData['rataRata'][$index] ?? null,
            ]);
        });

        return $normalizedDataWithOriginal;
    }

    public function performClustering()
    {
        $normalizedData = $this->normalizeSampahData()->toArray();
        $formattedData = array_map(fn($item) => [
            $item['normalized_volume'],
            $item['normalized_jarak'],
            $item['normalized_rata_rata_jarak'],
        ], $normalizedData);

        $k = 3;
        $result = $this->kmeansPlus($k, $formattedData);

        $clusteredData = [];
        foreach ($result['clusters'] as $clusterIndex => $cluster) {
            foreach ($cluster as $dataPoint) {
                $index = array_search($dataPoint, $formattedData);
                if ($index !== false) {
                    $normalizedData[$index]['cluster'] = $clusterIndex;
                    $normalizedData[$index]['prioritas'] = match ($clusterIndex) {
                        0 => 'Tinggi',
                        1 => 'Sedang',
                        2 => 'Rendah',
                    };
                }
            }
        }

        // Kelompokkan data berdasarkan cluster
        $groupedByCluster = collect($normalizedData)->groupBy('cluster')->sortKeys();
        // dd($groupedByCluster);

        // Kirim data terkelompok ke view
        return view('proses.index', compact('groupedByCluster'));
    }


    private function kmeansPlus($k, $data)
    {
        $clusters = [];
        $centroids = [];

        // Inisialisasi centroid pertama secara acak
        // $centroids[] = $data[array_rand($data)];
        $centroids[] = $data[18];

        // Pilih centroid yang tersisa menggunakan pendekatan K-Means++
        while (count($centroids) < $k) {
            $distances = array_map(function ($point) use ($centroids) {
                return min(array_map(function ($centroid) use ($point) {
                    return $this->euclideanDistance($point, $centroid);
                }, $centroids));
            }, $data);

            $cumulativeDistances = array_sum($distances);
            // $randomDistance = mt_rand() / mt_getrandmax() * $cumulativeDistances;
            $randomDistance = 0.26 / 0.5 * $cumulativeDistances;

            foreach ($data as $index => $point) {
                $randomDistance -= $distances[$index];
                if ($randomDistance <= 0) {
                    $centroids[] = $point;
                    break;
                }
            }
        }

        $iterations = 0;
        while ($iterations < 100) {
            $clusters = array_fill(0, $k, []);

            // Assign setiap titik ke centroid terdekat
            foreach ($data as $point) {
                $distances = array_map(fn($centroid) => $this->euclideanDistance($point, $centroid), $centroids);
                $closestCentroid = array_keys($distances, min($distances))[0];
                $clusters[$closestCentroid][] = $point;
            }

            // Update centroid berdasarkan rata-rata titik di setiap cluster
            $newCentroids = array_map(function ($cluster) {
                $clusterSize = count($cluster);
                if ($clusterSize === 0) return [0, 0, 0]; // Hindari pembagian dengan nol
                return array_map(fn(...$coords) => array_sum($coords) / $clusterSize, ...$cluster);
            }, $clusters);

            if ($centroids === $newCentroids) break;

            $centroids = $newCentroids;
            $iterations++;
        }

        return ['centroids' => $centroids, 'clusters' => $clusters];
    }

    /**
     * Method untuk menghitung jarak Euclidean antara dua titik.
     */
    private function euclideanDistance($point1, $point2)
    {
        return sqrt(array_sum(array_map(fn($a, $b) => pow($a - $b, 2), $point1, $point2)));
    }
}
