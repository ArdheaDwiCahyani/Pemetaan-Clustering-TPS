@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <!-- Div untuk menampilkan peta -->
            <div id="map" style="height: 470px; position: relative;">
                <!-- Legend Section -->
                <div id="legend" class="card"
                    style="width: 180px; height:170px; position: absolute; top: 10px; right: 10px; z-index: 1000;">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Legenda</h5>
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-row mb-2">
                                <div class="border"
                                    style="background-color: red; margin-right: 8px; width: 20px; height: 20px; border: solid;">
                                </div>
                                <span>Prioritas Tinggi</span>
                            </div>
                            <div class="d-flex flex-row mb-2">
                                <div class="border"
                                    style="background-color: yellow; margin-right: 8px; width: 20px; height: 20px; border: solid;">
                                </div>
                                <span>Prioritas Sedang</span>
                            </div>
                            <div class="d-flex flex-row mb-2">
                                <div class="border"
                                    style="background-color: green; margin-right: 8px; width: 20px; height: 20px; border: solid;">
                                </div>
                                <span>Prioritas Rendah</span>
                            </div>
                            <!-- Add more categories as needed -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Tambahkan pustaka yang dibutuhkan -->
    <!-- Tambahkan Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Leaflet JS dan CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">

    <script>
        // Inisialisasi peta dengan koordinat pusat (Malang)
        const map = L.map('map').setView([-7.957896707102229, 112.63006068659277], 11.5);

        // Tambahkan layer peta dasar dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);



        // Tambahkan GeoJSON untuk layer lurah.json
        const geojsonUrl = '/geojson/lurah.json'; // URL untuk data GeoJSON dari 'lurah.json'

        fetch(geojsonUrl)
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    style: function(feature) {
                        return {
                            color: "black", // Border warna
                            weight: 1, // Berat border
                            fillColor: "purple", // Warna isi
                            fillOpacity: 0.3 // Opasitas warna isi
                        };
                    }
                }).addTo(map);
            })
            .catch(error => {
                console.error('Error loading GeoJSON:', error);
            });

        // Ambil data GeoJSON berdasarkan tahun (contoh tahun 2024)
        const selectedYear = @json($selectedYear);
        const geojsonClusterUrl = `/proses/geojson/${selectedYear}`;

        // Memuat dan menambahkan marker berdasarkan cluster
        fetch(geojsonClusterUrl)
            .then(response => response.json())
            .then(data => {
                data.features.forEach(function(feature) {
                    // Menentukan warna marker berdasarkan cluster
                    const cluster = feature.properties.cluster;
                    let color;

                    // Menentukan warna marker berdasarkan nilai cluster
                    switch (cluster) {
                        case 0:
                            color = 'red';
                            fillOpacity = 0.6;
                            break;
                        case 1:
                            color = 'yellow';
                            break;
                        case 2:
                            color = 'green';
                            break;
                        default:
                            color = 'blue'; // Warna default untuk cluster lain
                    }

                    // Membuat ikon dengan warna sesuai cluster
                    const customBootstrapIcon = L.divIcon({
                        className: 'leaflet-bootstrap-icon', // Nama class untuk styling tambahan
                        html: `<i class="bi bi-geo-alt-fill" style="font-size: 24px; color: ${color};"></i>`, // Warna sesuai dengan cluster
                        iconSize: [30, 30], // Ukuran ikon
                        iconAnchor: [15, 30], // Titik jangkar dari ikon
                        popupAnchor: [0, -30] // Menyesuaikan posisi popup
                    });

                    // Cek apakah koordinat valid
                    if (feature.geometry && feature.geometry.coordinates.length === 2) {
                        const lat = feature.geometry.coordinates[1];
                        const lng = feature.geometry.coordinates[0];

                        // Menambahkan marker berbentuk circleMarker
                        L.marker([lat, lng], {
                                icon: customBootstrapIcon
                            })
                            .addTo(map)
                            .bindPopup(`
                            <b>${feature.properties.namaTPS}</b><br><br>
                            Kelurahan: ${feature.properties.kelurahan}<br>
                            Volume Sampah: ${feature.properties.volume}<br>
                            Jarak ke TPA: ${feature.properties.jarak}<br>
                            Rata-Rata Jarak: ${feature.properties.rata_rata_jarak}<br>
                            Cluster: ${feature.properties.cluster + 1}<br>
                            Prioritas: ${feature.properties.prioritas}
                        `);
                    } else {
                        console.error("Invalid coordinates for feature:", feature);
                    }
                });
            })
            .catch(error => {
                console.error('Error loading Cluster GeoJSON:', error);
            });
    </script>
@endsection
