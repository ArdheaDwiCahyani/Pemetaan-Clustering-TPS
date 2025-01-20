@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body pb-0 mb-0">
                        <form action="{{ route('proses.cluster') }}" method="POST">
                            <div class="form-group row align-items-center mb-0 pb-0">
                                @csrf
                                <label class="col-auto col-form-label mb-0 me-2" for="tahun">Tahun</label>
                                <div class="col-auto" style="width: 18%">
                                    <select name="tahun" id="tahun" class="form-select" required>
                                        <option value="pilih-tahun">Pilih Tahun</option>
                                        @foreach ($tahun as $data)
                                            <option value="{{ $data }}"
                                                {{ isset($selectedYear) && $selectedYear == $data ? 'selected' : '' }}>
                                                {{ $data }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto ms-auto">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary" id="replace-btn"
                                            style="min-width: 120px;">Replace</button>
                                        <a href="#" class="btn btn-primary" id="export-btn"
                                            style="min-width: 120px;">Export</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body" id="card-body">
                        <div id="hasil-clustering-container">
                            <!-- Menampilkan hasil clustering jika sudah diproses -->
                            @if (isset($groupedByCluster) && $groupedByCluster)
                                @foreach ($groupedByCluster as $clusterIndex => $clusterData)
                                    <h3>Cluster {{ (int) $clusterIndex + 1 }}</h3>
                                    <div class="table-responsive p-0">
                                        <table class="table table align-items-center mb-0" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th style="width: 100px;"
                                                        class="text-dark text-center text-sm font-weight-medium">No</th>
                                                    <th class="text-dark text-sm font-weight-medium px-0">Nama TPS</th>
                                                    <th class="text-dark text-sm text-center font-weight-medium">Volume
                                                        Sampah
                                                        <br>(Ton)
                                                    </th>
                                                    <th class="text-dark text-sm text-center font-weight-medium">Jarak ke
                                                        TPA
                                                        <br>(km)
                                                    </th>
                                                    <th class="text-dark text-sm text-center font-weight-medium">Rata-Rata
                                                        Jarak
                                                        <br>(km)
                                                    </th>
                                                    <th class="text-dark text-sm text-center font-weight-medium">Cluster
                                                    </th>
                                                    <th class="text-dark text-sm text-center font-weight-medium">Prioritas
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php($no = 1)
                                                @foreach ($clusterData as $data)
                                                    <tr>
                                                        <td class="text-dark text-center align-middle text-sm">
                                                            {{ $no++ }}</td>
                                                        <td class="text-dark align-middle text-sm text-wrap">
                                                            {{ $data['nama_tps'] }}</td>
                                                        <td class="text-dark text-center align-middle text-sm">
                                                            {{ $data['volume'] }}</td>
                                                        <td class="text-dark text-center align-middle text-sm">
                                                            {{ $data['jarak'] }}</td>
                                                        <td class="text-dark text-center align-middle text-sm">
                                                            {{ $data['rata_rata_jarak'] }}</td>
                                                        <td class="text-dark text-center align-middle text-sm">Cluster
                                                            {{ $data['cluster'] + 1 }}</td>
                                                        <td class="text-dark text-center align-middle text-sm">
                                                            {{ $data['prioritas'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                                <table class="table table align-items-center mb-0" cellspacing="0" id="hasil-clustering">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px;"
                                                class="text-dark text-center text-sm font-weight-medium">
                                                No</th>
                                            <th class="text-dark text-sm font-weight-medium px-0">Nama TPS</th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Volume Sampah
                                                <br>(Ton)
                                            </th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Jarak ke TPA
                                                <br>(km)
                                            </th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Rata-Rata Jarak
                                                <br>(km)
                                            </th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Cluster</th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Prioritas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be dynamically inserted here by JavaScript -->
                                    </tbody>
                                </table>
                            @else
                                <p class="pt-0 mt-0">Belum melakukan proses clustering. Silahkan pilih tahun dan klik tombol
                                    'Proses'.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- <script>
        // Event listener untuk mendeteksi perubahan pada elemen select tahun
        document.getElementById('tahun').addEventListener('change', function() {
            var selectedYear = this.value;
            var prosesBtn = document.getElementById('proses-btn');
            var exportBtn = document.getElementById('export-btn');

            // Jika tahun dipilih, aktifkan tombol
            if (selectedYear) {
                prosesBtn.removeAttribute('disabled');
                exportBtn.removeAttribute('disabled');
            } else {
                prosesBtn.setAttribute('disabled', 'disabled');
                exportBtn.setAttribute('disabled', 'disabled');
            }
        });
    </script> --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const selectTahun = document.getElementById('tahun');
        const exportBtn = document.getElementById('export-btn');

        // Ketika tahun dipilih, simpan ke localStorage
        selectTahun.addEventListener('change', function() {
            const selectedYear = selectTahun.value;
            if (selectedYear && selectedYear !== "pilih-tahun") {
                localStorage.setItem('selectedYear', selectedYear);
            } else {
                localStorage.removeItem('selectedYear');
            }
            updateExportUrl();
        });

        // Fungsi untuk memperbarui URL tombol Export
        function updateExportUrl() {
            const savedYear = localStorage.getItem('selectedYear');
            if (savedYear && savedYear !== "pilih-tahun") {
                exportBtn.href = `{{ url('proses/export') }}/${savedYear}`;
            } else {
                exportBtn.href = '#'; // Default jika tidak ada tahun yang dipilih
            }
        }

        // Set URL export saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateExportUrl();
        });

        // Validasi sebelum tombol Export diklik
        exportBtn.addEventListener('click', function(event) {
            const savedYear = localStorage.getItem('selectedYear');
            if (!savedYear || savedYear === "pilih-tahun") {
                alert('Silakan pilih tahun terlebih dahulu!');
                event.preventDefault(); // Cegah navigasi jika tahun tidak valid
            }
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#replace-btn').hide();
            // Event listener untuk mendeteksi perubahan pada elemen select tahun
            $('#tahun').on('change', function() {
                var selectedYear = $(this).val();
                if (selectedYear) {
                    // Menampilkan tombol replace dan menyembunyikan tombol proses
                    // $('#proses-btn').hide();
                    $('#replace-btn').show();
                    const initialValue = $('#tahun').val();
                    console.log(initialValue);
                    if (initialValue === 'pilih-tahun') {
                        $('#proses-btn').show();
                        $('#replace-btn').hide();
                    } else {
                        // $('#proses-btn').hide();
                        $('#replace-btn').show();
                    }

                    // Menambahkan event listener untuk tombol replace
                    $('#replace-btn').off('click').on('click', function() {
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Proses ini akan menggantikan clustering yang sudah ada.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, replace!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                console.log(result
                                    .isConfirmed); // Make sure this logs as expected

                                // Ensure selectedYear is available here
                                const url = '/proses/show-replace/' + selectedYear;
                                console.log(
                                    url); // Log URL yang dibentuk// Log the generated URL

                                $.ajax({
                                    url: url,
                                    method: 'GET',
                                    success: function(response) {
                                        if (response.status === 'success' &&
                                            response.groupedByCluster) {
                                            var container = $(
                                                '#hasil-clustering-container'
                                            );
                                            container
                                                .empty(); // Menghapus konten lama

                                            response.groupedByCluster.forEach(
                                                function(clusterData,
                                                    clusterIndex) {
                                                    var clusterHTML = `
                                        <h3>Cluster ${clusterIndex + 1}</h3>
                                        <div class="table-responsive p-0">
                                            <table class="table table align-items-center mb-0" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 100px;" class="text-dark text-center text-sm font-weight-medium">No</th>
                                                        <th class="text-dark text-sm font-weight-medium px-0">Nama TPS</th>
                                                        <th class="text-dark text-sm text-center font-weight-medium">Volume Sampah <br>(Ton)</th>
                                                        <th class="text-dark text-sm text-center font-weight-medium">Jarak ke TPA <br>(km)</th>
                                                        <th class="text-dark text-sm text-center font-weight-medium">Rata-Rata Jarak <br>(km)</th>
                                                        <th class="text-dark text-sm text-center font-weight-medium">Cluster</th>
                                                        <th class="text-dark text-sm text-center font-weight-medium">Prioritas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${clusterData.map((data, index) => `
                                                                                    <tr>
                                                                                        <td class="text-dark text-center align-middle text-sm">${index + 1}</td>
                                                                                        <td class="text-dark align-middle text-sm text-wrap">${data.nama_tps}</td>
                                                                                        <td class="text-dark text-center align-middle text-sm">${data.volume}</td>
                                                                                        <td class="text-dark text-center align-middle text-sm">${data.jarak}</td>
                                                                                        <td class="text-dark text-center align-middle text-sm">${data.rata_rata_jarak}</td>
                                                                                        <td class="text-dark text-center align-middle text-sm">Cluster ${data.cluster + 1}</td>
                                                                                        <td class="text-dark text-center align-middle text-sm">${data.prioritas}</td>
                                                                                    </tr>
                                                                                `).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    `;
                                                    container.append(
                                                        clusterHTML);
                                                });

                                            // Menampilkan pesan sukses
                                            Swal.fire({
                                                title: 'Sukses!',
                                                text: 'Clustering berhasil digantikan.',
                                                icon: 'success',
                                                confirmButtonText: 'OK'
                                            });

                                            // Tetap menyembunyikan tombol proses-btn
                                            // $('#proses-btn').hide();
                                        } else {
                                            Swal.fire('Gagal!', response
                                                .message ||
                                                'Terjadi kesalahan.',
                                                'error');
                                        }
                                    },
                                    error: function(xhr, error) {
                                        Swal.fire('Error!',
                                            'Terjadi kesalahan dalam memproses permintaan.',
                                            'error');
                                    }
                                });
                            }
                        });
                    });
                } else {
                    $('#replace-btn').hide();
                    $('#proses-btn').show();
                }

                // Mengirimkan request AJAX ke server untuk memproses tahun yang dipilih
                if (selectedYear === 'pilih-tahun') {
                    // Tampilkan pesan dan tombol 'Proses'
                    $('#message').show();
                    $('#proses-btn').show();
                    $('#replace-btn').hide();
                    $('#card-body').empty();
                    $('#card-body').html(`
                <p class="pt-0 mt-0">Belum melakukan proses clustering. Silahkan pilih tahun dan klik tombol 'Proses'.</p>
            `);
                } else {
                    // Sembunyikan pesan dan tampilkan tombol 'Replace'
                    $('#message').hide();
                    // $('#proses-btn').hide();
                    $('#replace-btn').show();

                    // Lakukan AJAX untuk mendapatkan data clustering
                    $.ajax({
                        url: '{{ route('proses.cluster') }}',
                        method: 'GET',
                        data: {
                            tahun: selectedYear,
                        },
                        success: function(response) {
                            if (response.status === 'success' && response.groupedByCluster) {
                                var groupedData = response.groupedByCluster;
                                var tableHTML = '';

                                // Loop untuk setiap kelompok data
                                groupedData.forEach(function(clusterData, index) {
                                    tableHTML += `
                                <h3 class="mt-3">Cluster ${index + 1}</h3>
                                <table class="table table align-items-center mb-0" cellspacing="0" id="hasil-clustering-${index}">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px;" class="text-dark text-center text-sm font-weight-medium">No</th>
                                            <th class="text-dark text-sm font-weight-medium px-0">Nama TPS</th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Volume Sampah <br>(Ton)</th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Jarak ke TPA <br>(km)</th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Rata-Rata Jarak <br>(km)</th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Cluster</th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Prioritas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;

                                    // Loop untuk memasukkan baris ke dalam tbody
                                    clusterData.forEach(function(tps, subIndex) {
                                        tableHTML += `
                                    <tr>
                                        <td class="text-dark text-center align-middle text-sm">${subIndex + 1}</td>
                                        <td class="text-dark align-middle text-sm text-wrap">${tps.nama_tps}</td>
                                        <td class="text-dark text-sm text-center font-weight-medium">${tps.volume}</td>
                                        <td class="text-dark text-sm text-center font-weight-medium">${tps.jarak}</td>
                                        <td class="text-dark text-sm text-center font-weight-medium">${tps.rata_rata_jarak}</td>
                                        <td class="text-dark text-sm text-center font-weight-medium">${tps.cluster+1}</td>
                                        <td class="text-dark text-sm text-center font-weight-medium">${tps.prioritas}</td>
                                    </tr>
                                `;
                                    });

                                    // Menutup tbody dan tabel
                                    tableHTML += `
                                </tbody>
                            </table>
                            `;

                                });

                                // Menambahkan tabel yang telah dibuat ke dalam div dengan id "card-body"
                                $('#card-body').html(tableHTML);

                            } else {
                                $('#card-body').html(
                                    `
                                <p class="mt-3">Belum melakukan proses clustering. Silahkan pilih tahun dan klik tombol 'Proses'.</p>`
                                );
                            }
                        },
                        error: function(xhr, error) {
                            alert('Terjadi kesalahan dalam memproses permintaan.');
                        }
                    });
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection
