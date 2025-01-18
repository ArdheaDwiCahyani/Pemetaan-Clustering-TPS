<?php

namespace App\Http\Controllers;

use App\Exports\ClusterExport;
use App\Models\ClusteringResult;
use App\Models\Sampah;
use App\Models\Tps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProsesController extends Controller
{
    // Fungsi untuk normalisasi data sampah berdasarkan tahun
    public function normalizeSampahData($tahun)
    {
        // Ambil semua data Sampah berdasarkan tahun
        $sampahData = Sampah::where('tahun', $tahun)->get();

        // Inisialisasi array untuk menyimpan data dan parameter
        $originalData = [];
        $volumeData = [];
        $jarakData = [];
        $rataRataData = [];

        // Iterasi melalui data Sampah dan menyusun data yang dibutuhkan
        foreach ($sampahData as $data) {
            // Ambil data TPS terkait
            $tps = $data->tps;

            // Inisialisasi entry asli untuk setiap TPS
            $originalEntry = [
                'tps_id' => $tps->id,
                'nama_tps' => $tps->namaTPS,
                'kelurahan' => $tps->kelurahan ? $tps->kelurahan->namaKelurahan : 'Tidak Diketahui',
                'volume' => $data->volumeSampah, // Mengambil volume sampah dari tabel Sampah
                'jarak' => $tps->jarakTPA, // Mengambil jarak dari tabel Tps
                'rata_rata_jarak' => $data->rata_rata_jarak, // Rata-rata jarak diambil dari accessor
                'longitude' => $tps->longitude,
                'latitude' => $tps->latitude,
            ];

            // Menyimpan nilai parameter untuk normalisasi
            $volumeData[] = $data->volumeSampah;
            $jarakData[] = $tps->jarakTPA;
            $rataRataData[] = $data->rata_rata_jarak;

            // Menambahkan entry asli ke dalam array
            $originalData[] = $originalEntry;
        }

        // Fungsi untuk normalisasi data
        $normalize = function ($data) {
            $min = !empty($data) ? min($data) : 0;
            $max = !empty($data) ? max($data) : 1;

            return array_map(fn($value) => ($max - $min) ? ($value - $min) / ($max - $min) : 0, $data);
        };

        // Normalisasi data berdasarkan volume, jarak, dan rata-rata jarak
        $normalizedVolume = $normalize($volumeData);
        $normalizedJarak = $normalize($jarakData);
        $normalizedRataRata = $normalize($rataRataData);

        // Menggabungkan data asli dengan data yang sudah dinormalisasi
        $normalizedDataWithOriginal = collect($originalData)->map(function ($original, $index) use ($normalizedVolume, $normalizedJarak, $normalizedRataRata) {
            return array_merge($original, [
                'normalized_volume' => $normalizedVolume[$index] ?? null,
                'normalized_jarak' => $normalizedJarak[$index] ?? null,
                'normalized_rata_rata_jarak' => $normalizedRataRata[$index] ?? null,
            ]);
        });

        // Mengembalikan data yang sudah dinormalisasi bersama data asli
        return $normalizedDataWithOriginal;
    }

    // Fungsi untuk melakukan K-Means++ (dengan pengambilan centroid yang lebih cermat)
    private function kmeansPlus($k, $data)
    {
        if (empty($data)) {
            throw new \Exception("Data untuk klasterisasi kosong.");
        }

        $clusters = [];
        $centroids = [];

        // Menentukan centroid pertama dengan normalized_volume tertinggi
        $maxVolumeIndex = array_search(max(array_column($data, 0)), array_column($data, 0)); // Mengambil indeks dengan nilai normalized_volume tertinggi
        $centroids[] = $data[$maxVolumeIndex];

        // Logging informasi mengenai centroid pertama yang dipilih
        Log::info('Centroid pertama dipilih berdasarkan normalized_volume tertinggi:', [
            'index' => $maxVolumeIndex,
            'centroid' => $data[$maxVolumeIndex]
        ]);

        mt_srand(170503);
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

    // Fungsi untuk menghitung Silhouette Score
    private function calculateSilhouetteScore($clusters, $data)
    {
        $totalScore = 0;
        $totalPoints = 0;

        foreach ($clusters as $clusterIndex => $cluster) {
            foreach ($cluster as $point) {
                $a = $this->calculateAverageDistance($point, $cluster);

                $b = INF;
                foreach ($clusters as $otherIndex => $otherCluster) {
                    if ($clusterIndex !== $otherIndex) {
                        $b = min($b, $this->calculateAverageDistance($point, $otherCluster));
                    }
                }

                $score = ($b - $a) / max($a, $b);
                $totalScore += $score;
                $totalPoints++;
            }
        }

        return $totalPoints ? $totalScore / $totalPoints : 0;
    }

    // Fungsi untuk menghitung rata-rata jarak ke titik dalam cluster
    private function calculateAverageDistance($point, $cluster)
    {
        $totalDistance = 0;
        $totalPoints = count($cluster);

        foreach ($cluster as $otherPoint) {
            $totalDistance += $this->euclideanDistance($point, $otherPoint);
        }

        return $totalPoints ? $totalDistance / $totalPoints : 0;
    }

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

            session(['groupedByCluster' => $groupedByCluster]);
            session()->put('hasil_clustering', 'true');
            session()->put('tahun_clustering', $selectedYear);
        } else {
            session()->forget(['hasil_clustering', 'tahun_clustering']);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'groupedByCluster' => $groupedByCluster,
            ]);
        }

        return view('proses.index', compact('tahun', 'selectedYear', 'groupedByCluster'));
    }

    public function showProsesReplace(Request $request, $tahun)
    {
        try {
            // Validasi input tahun
            $validatedYear = intval($tahun);
            if (!$validatedYear) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tahun tidak valid.'
                ], 400);
            }

            // Periksa dan hapus data di model ClusteringResult jika field 'tahun' sesuai
            $deletedRows = ClusteringResult::where('tahun', $validatedYear)->delete();

            // Jika tidak ada data yang dihapus, beri informasi kepada pengguna
            if ($deletedRows === 0) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Tidak ada data yang ditemukan untuk tahun ini.'
                ], 200);
            }

            $tahun = Sampah::select('tahun')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->pluck('tahun')
                ->toArray();

            if ($validatedYear && in_array($validatedYear, $tahun)) {
                $groupedByCluster = $this->performClustering($validatedYear);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Proses clustering berhasil dilakukan.',
                    'groupedByCluster' => $groupedByCluster
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tahun yang dipilih belum ada data.'
                ], 404);
            }
        } catch (\Exception $e) {
            // Tangani error jika ada
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat melakukan proses clustering.',
                'error' => $e->getMessage()
            ], 500);
        }
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

        $silhouetteScore = $this->calculateSilhouetteScore($result['clusters'], $formattedData);
        Log::info('Silhouette Score:', ['score' => $silhouetteScore]);

        $groupedByCluster = collect($normalizedData)->groupBy('cluster')->sortKeys();

        foreach ($groupedByCluster as $clusterIndex => $cluster) {
            foreach ($cluster as $dataPoint) {
                // Pastikan dataPoint memiliki tps_id dan data yang valid untuk disimpan
                try {
                    ClusteringResult::create([
                        'tps_id' => $dataPoint['tps_id'],
                        'normalized_volume' => $dataPoint['normalized_volume'],
                        'normalized_jarak' => $dataPoint['normalized_jarak'],
                        'normalized_rata_rata_jarak' => $dataPoint['normalized_rata_rata_jarak'],
                        'cluster' => $clusterIndex,
                        'prioritas' => $dataPoint['prioritas'],
                        'tahun' => $tahun,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Gagal menyimpan clustering result', ['error' => $e->getMessage()]);
                }
            }
        }

        return $groupedByCluster;
    }

    public function processClustering(Request $request)
    {
        $tahun = $request->input('tahun');

        // Cek apakah data hasil clustering sudah ada untuk tahun yang dipilih
        $existingResults = ClusteringResult::where('tahun', $tahun)->exists();


        if ($existingResults && !$request->input('force')) {
            return response()->json(['status' => 'exists', 'message' => 'Hasil Clustering untuk tahun ini sudah ada. Proses ulang?']);
        }

        // Jika sudah ada, dan ada parameter force, hapus hasil lama dan proses ulang
        if ($existingResults && $request->input('force')) {
            ClusteringResult::where('tahun', $tahun)->delete();
        }

        // Proses clustering dan simpan hasilnya
        $groupedByCluster = $this->performClustering($tahun); // Proses klasterisasi

        // Mengembalikan respon setelah selesai
        return response()->json([
            'status' => 'success',
            'message' => 'Clustering selesai dan data telah tersimpan'
        ]);
    }

    public function exportCluster($tahun)
    {
        $clusters = session('groupedByCluster');
        // dd($clusters);
        if (!$clusters) {
            return redirect()->back()->with('error', 'Data hasil clustering belum tersedia.');
        }

        $dataForExport = collect();
        foreach ($clusters as $clusterIndex => $clusterData) {
            foreach ($clusterData as $data) {
                $dataForExport->push([
                    'Nama TPS' => $data['namaTPS'],
                    'Volume Sampah (Ton)' => $data['volume'],
                    'Jarak ke TPA (Km)' => $data['jarak'],
                    'Rata-Rata Jarak (Km)' => $data['rata_rata_jarak'],
                    'Cluster' => 'Cluster' . ($data['cluster'] + 1),
                    'Prioritas' => $data['prioritas'],
                ]);
            }
        }

        return Excel::download(new ClusterExport($dataForExport), "Hasil_Clustering_$tahun.xlsx");
    }
}
