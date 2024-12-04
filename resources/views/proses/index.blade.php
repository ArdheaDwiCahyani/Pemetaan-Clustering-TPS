@extends('layouts.app')

@section('title', 'Proses Clustering')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('proses.cluster') }}" method="POST">
                            @csrf
                            <div class="form-group row align-items-center mb-4">
                                <label class="col-auto col-form-label mb-0 me-2" for="tahun">Tahun</label>
                                <div class="col-auto" style="width: 18%">
                                    <select name="tahun" id="tahun" class="form-select" required>
                                        <option value="" hidden> Pilih Tahun </option>
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
                                        <button type="submit" class="btn btn-outline-primary" id="proses-btn"
                                            style="min-width: 120px;">Proses</button>
                                        <a href="{{ isset($selectedYear) && $selectedYear ? route('hasil.export', ['tahun' => $selectedYear]) : '#' }}"
                                            class="btn btn-primary" id="export-btn" style="min-width: 120px;"
                                            {{ !isset($selectedYear) || !$selectedYear ? : '' }}>
                                            Export</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Menampilkan hasil clustering jika sudah diproses -->
                        @if (isset($groupedByCluster) && $groupedByCluster)
                            @foreach ($groupedByCluster as $clusterIndex => $clusterData)
                                <h3>Cluster {{ (int) $clusterIndex + 1 }}</h3>
                                <div class="table-responsive p-0">
                                    <table class="table table align-items-center mb-0" cellspacing="0"
                                        id="hasil-clustering">
                                        <thead>
                                            <tr>
                                                <th style="width: 100px;"
                                                    class="text-dark text-center text-sm font-weight-medium">No</th>
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
                        @else
                            <p class="mt-3">Belum melakukan proses clustering. Silahkan pilih tahun dan klik tombol
                                'Proses'.</p>
                        @endif
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
        $(document).ready(function() {
            // Event listener untuk mendeteksi perubahan pada elemen select tahun
            $('#tahun').on('change', function() {
                var selectedYear = $(this).val();

                // Menyembunyikan hasil clustering jika tahun baru dipilih
                // $('#proses-btn').prop('disabled', true);
                // $('#export-btn').prop('disabled', true);
                $('#hasil-clustering tbody').html(''); // Hapus data hasil clustering yang sebelumnya

                // Mengirimkan request AJAX ke server untuk memproses tahun yang dipilih
                $.ajax({
                    url: '{{ route('proses.cluster') }}',
                    method: 'GET',
                    data: {
                        tahun: selectedYear,
                    },
                    success: function(response) {
                        // if (response.status === 'success') {
                        // if (response.groupedByCluster) {
                        if (response.status === 'success' && response.groupedByCluster) {
                            console.log(response.groupedByCluster);
                            // Enable tombol jika proses clustering berhasil

                            // $('#proses-btn').prop('disabled', false);
                            // $('#export-btn').prop('disabled', false);

                            // Update hasil clustering di halaman
                            // var rows = '';
                            // response.selectedYear.forEach(function(data, index) {
                            //     rows += `
                            //                 <tr>
                            //                     <td class="text-dark text-center align-middle text-sm">${index + 1}</td>
                            //                     <td class="text-dark align-middle text-sm text-wrap">${data.namaTPS || ''}</td>
                            //                     <td class="text-dark text-center align-middle text-sm">${data.volume || ''}</td>
                            //                     <td class="text-dark text-center align-middle text-sm">${data.jarak || ''}</td>
                            //                     <td class="text-dark text-center align-middle text-sm">${data.rata_rata_jarak || ''}</td>
                            //                     <td class="text-dark text-center align-middle text-sm">Cluster ${data.cluster + 1}</td>
                            //                     <td class="text-dark text-center align-middle text-sm">${data.prioritas || ''}</td>
                            //                 </tr>
                            //             `;
                            // });

                            // console.log(rows);


                            // $('#hasil-clustering tbody').html(rows);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, error) {
                        alert('Terjadi kesalahan dalam memproses permintaan.');
                    }
                });
            });

            // Event listener untuk tombol proses
            // $('#proses-btn').on('click', function() {
            //     var selectedYear = $('#tahun').val();
            //     if (selectedYear) {
            //         // Menampilkan SweetAlert konfirmasi sebelum proses
            //         Swal.fire({
            //             title: 'Apakah Anda yakin?',
            //             text: "Proses clustering akan dijalankan!",
            //             icon: 'warning',
            //             showCancelButton: true,
            //             confirmButtonText: 'Ya, Proses',
            //             cancelButtonText: 'Batal'
            //         }).then((result) => {
            //             if (result.isConfirmed) {
            //                 // Kirim permintaan AJAX untuk memulai clustering
            //                 $.ajax({
            //                     url: '{{ route('proses.cluster2') }}',
            //                     method: 'POST',
            //                     data: {
            //                         _token: '{{ csrf_token() }}',
            //                         tahun: selectedYear
            //                     },
            //                     success: function(response) {
            //                         if (response.status === 'success') {
            //                             Swal.fire(
            //                                 'Berhasil!',
            //                                 'Proses clustering selesai.',
            //                                 'success'
            //                             );
            //                         } else {
            //                             Swal.fire(
            //                                 'Gagal!',
            //                                 response.message,
            //                                 'error'
            //                             );
            //                         }
            //                     },
            //                     error: function() {
            //                         Swal.fire(
            //                             'Gagal!',
            //                             'Terjadi kesalahan dalam memproses permintaan.',
            //                             'error'
            //                         );
            //                     }
            //                 });
            //             }
            //         });
            //     }
            // });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection
