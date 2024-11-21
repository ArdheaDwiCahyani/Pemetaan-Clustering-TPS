<?php

namespace App\Http\Controllers;

use App\Models\Sampah;
use App\Models\Tps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProsesController extends Controller
{
    // Fungsi untuk menampilkan halaman proses dengan dropdown tahun
    public function showProses(Request $request)
    {
        // Mengambil tahun yang ada dalam tabel Sampah
        $tahun = Sampah::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Menangani kasus saat tahun belum dipilih atau tidak ada data
        $selectedYear = $request->input('tahun');

        $groupedByCluster = null;

        if ($selectedYear && in_array($selectedYear, $tahun)) {
            // Jika tahun valid dan dipilih
            $groupedByCluster = $this->performClustering($selectedYear);
        }
        

        return view('proses.index', compact('tahun', 'selectedYear', 'groupedByCluster'));
    }

    // Fungsi untuk normalisasi data sampah berdasarkan tahun
    public function normalizeSampahData($tahun)
    {
        // Mengambil data sampah yang terkait dengan tahun tertentu
        $sampahData = Tps::with([
            'parameterSampah' => function ($query) {
                $query->whereIn('namaParameter', ['Volume Sampah', 'Rata-Rata Jarak'])->withPivot('nilai_parameter');
            },
            'parameterTps' => function ($query) {
                $query->where('namaParameter', 'Jarak ke TPA')->withPivot('nilai_parameter');
            },
            'kelurahan'
        ])
            ->whereHas('sampah', function ($query) use ($tahun) {
                $query->where('tahun', $tahun);
            })->get();


        // Inisialisasi array untuk menyimpan data normalisasi
        $volumeData = [];
        $jarakData = [];
        $rataRataData = [];

        // Menyimpan data asli untuk setiap TPS
        $originalData = [];

        foreach ($sampahData as $tps) {
            $originalEntry = [
                'tps_id' => $tps->id,
                'namaTPS' => $tps->namaTPS,
                'kelurahan' => $tps->kelurahan ? $tps->kelurahan->namaKelurahan : null,
                'volume' => null,
                'jarak' => null,
                'rata_rata_jarak' => null,
                'longitude' => $tps->longitude,
                'latitude' => $tps->latitude,
            ];

            // Menyimpan nilai parameter yang sesuai
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

        // Fungsi untuk normalisasi data
        $normalize = function ($data) {
            $min = !empty($data) ? min($data) : 0;
            $max = !empty($data) ? max($data) : 1;
            return array_map(fn($value) => ($max - $min) ? ($value - $min) / ($max - $min) : 0, $data);
        };

        // Normalisasi data berdasarkan volume, jarak, dan rata-rata jarak
        $normalizedData = [
            'volume' => $normalize($volumeData),
            'jarak' => $normalize($jarakData),
            'rataRata' => $normalize($rataRataData),
        ];

        // Menggabungkan data asli dengan data yang sudah dinormalisasi
        $normalizedDataWithOriginal = collect($originalData)->map(function ($original, $index) use ($normalizedData) {
            return array_merge($original, [
                'normalized_volume' => $normalizedData['volume'][$index] ?? null,
                'normalized_jarak' => $normalizedData['jarak'][$index] ?? null,
                'normalized_rata_rata_jarak' => $normalizedData['rataRata'][$index] ?? null,
            ]);
        });
        return $normalizedDataWithOriginal;
    }

    // Fungsi untuk melakukan klasterisasi dengan K-Means++
    public function performClustering($tahun)
    {
        // Mengambil dan menormalisasi data sampah berdasarkan tahun
        $normalizedData = $this->normalizeSampahData($tahun)->toArray();

        // Mengecek apakah data yang dinormalisasi kosong
        if (empty($normalizedData)) {
            return redirect()->back()->with('error', 'Data untuk tahun tersebut tidak tersedia untuk klasterisasi.');
        }

        // Melakukan klasterisasi dan mengembalikan hasil
        $formattedData = array_map(fn($item) => [
            $item['normalized_volume'],
            $item['normalized_jarak'],
            $item['normalized_rata_rata_jarak'],
        ], $normalizedData);

        // Jumlah klaster yang diinginkan
        $k = 3;

        // Menjalankan algoritma K-Means++
        $result = $this->kmeansPlus($k, $formattedData);

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

        $groupedByCluster = collect($normalizedData)->groupBy('cluster')->sortKeys();
        // dd($groupedByCluster);

        return $groupedByCluster;
    }

    // Fungsi untuk melakukan K-Means++ (dengan pengambilan centroid yang lebih cermat)
    private function kmeansPlus($k, $data)
    {
        if (empty($data)) {
            throw new \Exception("Data untuk klasterisasi kosong.");
        }

        $clusters = [];
        $centroids = [];

        // Inisialisasi centroid pertama secara spesifik, misalnya data ke-10 sebagai centroid pertama
        $centroids[] = $data[9];

        // Logging informasi mengenai centroid yang dipilih
        Log::info('Centroid pertama dipilih:', [
            'index' => 9,
            'centroid' => $data[9]
        ]);

        // Memilih centroid yang tersisa menggunakan K-Means++
        // Pilih centroid yang tersisa menggunakan K-Means++
        while (count($centroids) < $k) {
            // Menghitung D(x)^2 untuk setiap titik data
            $distancesSquared = array_map(function ($point) use ($centroids) {
                $minDistance = min(array_map(function ($centroid) use ($point) {
                    return $this->euclideanDistance($point, $centroid);
                }, $centroids));
                return pow($minDistance, 2);
            }, $data);

            // Menghitung total D(x)^2 untuk semua titik
            $totalDistanceSquared = array_sum($distancesSquared);

            // Menghitung nilai K untuk setiap titik (probabilitas berdasarkan D(x)^2)
            $probabilities = array_map(function ($distanceSquared) use ($totalDistanceSquared) {
                return $distanceSquared / $totalDistanceSquared;
            }, $distancesSquared);

            // Menghitung jarak kumulatif untuk memilih centroid berikutnya secara acak berdasarkan distribusi probabilitas K
            $cumulativeDistances = [];
            $sum = 0;
            foreach ($probabilities as $probability) {
                $sum += $probability;
                $cumulativeDistances[] = $sum;
            }

            $randomDistance = mt_rand() / mt_getrandmax(); // Nilai acak antara 0 dan 1
            // $randomDistance = 0.454214381731215;

            // Menemukan titik yang dipilih berdasarkan randomDistance
            foreach ($data as $index => $point) {
                if ($randomDistance <= $cumulativeDistances[$index]) {
                    $centroids[] = $point;
                    Log::info('Centroid baru dipilih:', ['index' => $index, 'point' => $point, 'random value' => $randomDistance]);
                    break;
                }
            }
        }

        // Proses klasterisasi dengan centroid yang sudah ada
        $iterations = 0;
        while ($iterations < 100) {
            $clusters = array_fill(0, $k, []);

            // Menetapkan setiap titik ke centroid terdekat
            foreach ($data as $point) {
                $distances = array_map(fn($centroid) => $this->euclideanDistance($point, $centroid), $centroids);
                $closestCentroid = array_keys($distances, min($distances))[0];
                $clusters[$closestCentroid][] = $point;
            }

            // Memperbarui centroid berdasarkan rata-rata titik dalam setiap cluster
            $newCentroids = array_map(function ($cluster) {
                $clusterSize = count($cluster);
                if ($clusterSize === 0) return [0, 0, 0]; // Hindari pembagian dengan nol
                return array_map(fn(...$coords) => array_sum($coords) / $clusterSize, ...$cluster);
            }, $clusters);

            // Jika centroid tidak berubah, keluar dari loop
            if ($centroids === $newCentroids) break;

            $centroids = $newCentroids;
            $iterations++;
        }

        return ['centroids' => $centroids, 'clusters' => $clusters];
    }

    // Fungsi untuk menghitung jarak Euclidean antara dua titik
    private function euclideanDistance($point1, $point2)
    {
        return sqrt(array_sum(array_map(fn($a, $b) => pow($a - $b, 2), $point1, $point2)));
    }

    //pemetaan
    public function exportToGeoJSON($groupedByCluster)
    {
        $features = [];
        foreach ($groupedByCluster as $cluster => $tpsList) {
            foreach ($tpsList as $tps) {
                // Pastikan longitude dan latitude adalah tipe numerik
                $longitude = (float) $tps['longitude'];
                $latitude = (float) $tps['latitude'];

                // Periksa apakah kelurahan adalah string atau array
                $kelurahan = is_array($tps['kelurahan']) ? $tps['kelurahan']['namaKelurahan'] : $tps['kelurahan'];

                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$longitude, $latitude],
                    ],
                    'properties' => [
                        'namaTPS' => $tps['namaTPS'],
                        'kelurahan' => $kelurahan ?? null,
                        'volume' => $tps['volume'],
                        'jarak' => $tps['jarak'],
                        'rata_rata_jarak' => $tps['rata_rata_jarak'],
                        'cluster' => $tps['cluster'],
                        'prioritas' => $tps['prioritas'],
                    ],
                ];
            }
        }

        return json_encode([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    //endpoint u/ akses json
    public function geojsonData($tahun)
    {
        $groupedByCluster = $this->performClustering($tahun);
        return response($this->exportToGeoJSON($groupedByCluster), 200)->header('Content-Type', 'application/json');
    }

    public function showMap(Request $request)
    {
        // Mendapatkan input 'tahun' dari URL, default ke tahun saat ini jika tidak ada
        $selectedYear = $request->input('tahun', optional(Sampah::first())->tahun);

        // Mendapatkan hasil klasterisasi berdasarkan tahun
        $groupedByCluster = $this->performClustering($selectedYear);

        // Mengirimkan nilai tahun dan hasil klasterisasi ke view
        $tahun = Sampah::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();
        return view('proses.map', compact('tahun', 'selectedYear', 'groupedByCluster'));
    }
}
