@extends('layouts.app')

@section('title', 'Proses Clustering')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('proses.cluster') }}" method="POST">
                            <div class="form-group row align-items-center mb-4">
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
                                        {{-- <button type="submit" class="btn btn-outline-primary" id="proses-btn"
                                            style="min-width: 120px;">Proses</button> --}}
                                        <button type="button" class="btn btn-outline-primary" id="replace-btn"
                                            style="min-width: 120px;">Replace</button>
                                        <a href="{{ isset($selectedYear) && $selectedYear ? route('hasil.export', ['tahun' => $selectedYear]) : '#' }}"
                                            class="btn btn-primary" id="export-btn" style="min-width: 120px;"
                                            {{ !isset($selectedYear) || !$selectedYear ?: '' }}>
                                            Export</a>
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
                                                            {{ $data['namaTPS'] }}</td>
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
                                <p class="mt-3">Belum melakukan proses clustering. Silahkan pilih tahun dan klik tombol
                                    'Proses'.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

                                            // Gunakan Object.entries untuk mengiterasi groupedByCluster
                                            Object.entries(response
                                                .groupedByCluster).forEach(
                                                function([clusterName,
                                                    clusterData
                                                ], clusterIndex) {
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
                                                                                <td class="text-dark align-middle text-sm text-wrap">${data.namaTPS}</td>
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
                                                }
                                            );

                                            // Menampilkan pesan sukses
                                            Swal.fire({
                                                title: 'Sukses!',
                                                text: 'Clustering berhasil digantikan.',
                                                icon: 'success',
                                                confirmButtonText: 'OK'
                                            });

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
                <p class="mt-3">Belum melakukan proses clustering. Silahkan pilih tahun dan klik tombol 'Proses'.</p>
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

                                // Loop untuk setiap cluster dalam groupedByCluster
                                Object.keys(groupedData).forEach(function(clusterKey, index) {
                                    var clusterData = groupedData[clusterKey];

                                    tableHTML += `
                                        <h3>Cluster ${index + 1}</h3>
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

                                    // Loop untuk memasukkan data TPS ke dalam tabel
                                    clusterData.forEach(function(tps, subIndex) {
                                        tableHTML += `
                                            <tr>
                                                <td class="text-center">${subIndex + 1}</td>
                                                <td>${tps.namaTPS}</td>
                                                <td class="text-center">${tps.volume}</td>
                                                <td class="text-center">${tps.jarak}</td>
                                                <td class="text-center">${tps.rata_rata_jarak}</td>
                                                <td class="text-center">${index + 1}</td>
                                                <td class="text-center">-</td>
                                            </tr>
                                        `;
                                    });

                                    tableHTML += `
                                            </tbody>
                                        </table>
                                    `;
                                });

                                // Menambahkan tabel ke dalam div dengan id "card-body"
                                $('#card-body').html(tableHTML);
                            } else {
                                $('#card-body').html(
                                    `<p class="mt-3">Belum melakukan proses clustering. Silahkan pilih tahun dan klik tombol 'Proses'.</p>`
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
