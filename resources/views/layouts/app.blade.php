<!--
=========================================================
* Argon Dashboard 2 - v2.0.4
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logoDLH2.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDLH2.png') }}">
    <title>
        DLH KOTA MALANG
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <!-- <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" /> -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/styles/choices.min.css" />
    <style>
        #subMenu {
            display: none;
        }
    </style>
</head>

<!-- SIDEBAR -->

<body class="g-sidenav-show bg-primary">
    <div class="position-absolute w-100"></div>
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        @include('layouts.navbar')

        <!-- Content -->
        @yield('content')
    </main>

    <!--   Core JS Files   -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/scripts/choices.min.js"></script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#5e72e4",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#fbfbfb',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(function() {
            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var link = $(this).attr("href");

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#017534",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: link,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: "Error!",
                                    text: xhr.responseJSON.message,
                                    icon: "error"
                                });
                            }
                        })
                    }
                });
            });
        });
    </script>

    <!-- Mengatur icon dropdown -->
    <style>
        /* Pastikan submenu dalam kondisi default tersembunyi */
        #subMenu {
            display: none;
        }
    </style>

    <!-- choices dropdown -->
    <script>
        $(document).ready(function() {
            document.querySelectorAll('.choices-single').forEach(function(element) {
                // Inisialisasi Choices.js untuk setiap elemen
                new Choices(element, {
                    searchEnabled: true, // Aktifkan pencarian
                    shouldSort: false, // Jangan urutkan pilihan secara otomatis
                    itemSelectText: '', // Kosongkan teks "Press to select" (opsional)
                });
            });
        })
    </script>

    <script>
        $(document).ready(function() {
            let submenuOpen = false; // Status submenu

            // Toggle submenu hanya ketika menu Data Wilayah diklik
            $('#dataWilayahDropdown').on('click', function(e) {
                e.preventDefault(); // Mencegah default behavior link
                submenuOpen = !submenuOpen; // Toggle status submenu

                if (submenuOpen) {
                    $('#subMenu').css('display', 'block'); // Tampilkan submenu
                    $('#dropdownIcon').removeClass('bi-chevron-down').addClass('bi-chevron-up');
                } else {
                    $('#subMenu').css('display', 'none'); // Sembunyikan submenu
                    $('#dropdownIcon').removeClass('bi-chevron-up').addClass('bi-chevron-down');
                }
            });

            // Mencegah klik pada subMenu menutup submenu
            $('#subMenu').on('click', function(e) {
                e.stopPropagation();
            });

            // Klik di luar #dataWilayahDropdown dan #subMenu menutup submenu
            $(document).on('click', function(e) {
                if (submenuOpen && !$(e.target).closest('#dataWilayahDropdown, #subMenu').length) {
                    submenuOpen = false;
                    $('#subMenu').css('display', 'none'); // Sembunyikan submenu
                    $('#dropdownIcon').removeClass('bi-chevron-up').addClass('bi-chevron-down');
                }
            });
        });
    </script>

    {{-- fitur search --}}
    <script>
        $('#globalSearchInput').on('keyup', function() {
            var query = $(this).val();
            var type = $('#dataTable').data('type'); // Misalnya, jenis pencarian saat ini adalah kelurahan
            // console.log(type);

            $.ajax({
                url: '{{ route('search') }}',
                method: 'GET',
                data: {
                    query: query,
                    type: type // Kirimkan tipe pencarian
                },
                success: function(response) {
                    var tableBody = $('#table-body');
                    tableBody.empty();

                    if (response.results.length > 0) {
                        // var no = 1;
                        response.results.forEach(function(item, index) {
                            var row = ''
                            if (type == 'kelurahan') {
                                var row = `
                                            <tr>
                                                <td class="text-dark text-center align-middle text-sm">${index + 1}</td>
                                                        <td class="text-dark align-middle text-sm px-5">${item.namaKelurahan}</td>
                                                        <td class="text-dark align-middle text-sm px-5">${item.kecamatan ? item.kecamatan.namaKecamatan : ''}</td>
                                                        <td class="text-center align-middle text-center icon-lg px-0">
                                                            <a href="/kelurahan/edit/${item.id}">
                                                                <i class="fa-solid fa-edit btn-outline-success"
                                                                style="margin-right: 5px;"></i>
                                                            </a>
                                                            <a href="/kelurahan/hapus/${item.id}" id="delete">
                                                                <i class="fa-solid fa-trash btn-outline-danger"></i>
                                                            </a>
                                                        </td>
                                            </tr>`;
                            } else if (type == 'kecamatan') {
                                row = `
                                        <tr>
                                            <td class="text-dark text-center align-middle text-sm">${index + 1}</td>
                                            <td class="text-dark align-middle text-sm px-6">${item.namaKecamatan}</td>
                                            <td class="text-center align-middle text-center icon-lg px-0">
                                                <a href="/kecamatan/edit/${item.id}">
                                                    <i class="fa-solid fa-edit btn-outline-success"
                                                    style="margin-right: 5px;"></i>
                                                </a>
                                                <a href="/kecamatan/hapus/${item.id}" id="delete">
                                                    <i class="fa-solid fa-trash btn-outline-danger"></i>
                                                </a>
                                            </td>
                                        </tr>`;

                            } else if (type == 'tps') {
                                row = `
                                    <tr>
                                            <td class="text-dark text-center align-middle text-sm">${index + 1}</td>
                                            <td style="width: 200px;" class="text-dark align-middle text-sm text-wrap px-2">${item.namaTPS}</td>
                                            <td class="text-dark align-middle text-sm px-0">${item.kelurahan}</td>
                                            <td class="text-dark text-center align-middle text-sm">${item.jarak}</td>
                                            <td class="text-center align-middle text-center icon-lg px-0">
                                                <a href="/kecamatan/edit/${item.id}">
                                                    <i class="fa-solid fa-edit btn-outline-success"
                                                    style="margin-right: 5px;"></i>
                                                </a>
                                                <a href="/kecamatan/hapus/${item.id}" id="delete">
                                                    <i class="fa-solid fa-trash btn-outline-danger"></i>
                                                </a>
                                            </td>
                                        </tr>`;

                            } else if (type == 'jarak') {
                                row = `
                                        <tr>
                                            <td class="text-dark text-center align-middle text-sm">${index + 1}</td>
                                            <td style="width: 200px;" class="text-dark align-middle text-sm text-wrap px-2">${item.tpsAsal}</td>
                                            <td class="text-dark align-middle text-sm px-0">${item.tpsTujuan}</td>
                                            <td class="text-dark text-center align-middle text-sm">${item.jarak}</td>
                                            <td class="text-center align-middle text-center icon-lg px-0">
                                                <a href="/kecamatan/edit/${item.id}">
                                                    <i class="fa-solid fa-edit btn-outline-success"
                                                    style="margin-right: 5px;"></i>
                                                </a>
                                                <a href="/kecamatan/hapus/${item.id}" id="delete">
                                                    <i class="fa-solid fa-trash btn-outline-danger"></i>
                                                </a>
                                            </td>
                                        </tr>`;
                            } else if (type == 'sampah') {
                                row = `
                                        <tr>
                                            <td class="text-dark text-center align-middle text-sm">${index + 1}</td>
                                            <td style="width: 200px;" class="text-dark align-middle text-sm text-wrap px-2">${item.namaTPS}</td>
                                            <td class="text-dark align-middle text-sm px-0">${item.tahun}</td>
                                            <td class="text-dark text-center align-middle text-sm">${item.volume}</td>
                                            <td class="text-dark text-center align-middle text-sm">${item.jarakTPA}</td>
                                            <td class="text-dark text-center align-middle text-sm">${item.rataRataJarak}</td>
                                            <td class="text-center align-middle text-center icon-lg px-0">
                                                <a href="/kecamatan/edit/${item.id}">
                                                    <i class="fa-solid fa-edit btn-outline-success"
                                                    style="margin-right: 5px;"></i>
                                                </a>
                                                <a href="/kecamatan/hapus/${item.id}" id="delete">
                                                    <i class="fa-solid fa-trash btn-outline-danger"></i>
                                                </a>
                                            </td>
                                        </tr>`;
                            }

                            tableBody.append(row);
                        });
                    } else {
                        tableBody.append('<tr><td colspan="3">Tidak ada data ditemukan</td></tr>');
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            var type = $('#dataTable').data('type');
            // console.log(type); 

            $('#globalSearchInput').on('keyup', function() {
                var query = $(this).val(); // Ambil nilai input pencarian

                // Kirim permintaan AJAX untuk pencarian
                $.ajax({
                    url: '/search', // URL untuk melakukan pencarian
                    method: 'GET;',
                    data: {
                        query: query,
                        type: type, // Gunakan type yang sudah diambil dari data-type
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        var tableBody = $('#table-body');
                        tableBody.empty(); // Bersihkan tabel

                        if (response.results.length > 0) {
                            // Jika data ditemukan, tampilkan di tabel
                            response.results.forEach(function(item, index) {
                                var row = '';

                                var type = item.type;

                                switch (type) {
                                    case 'kecamatan':
                                        row = `
                                <tr>
                                    <td class="text-dark text-center align-middle text-sm">${index + 1}</td>
                                    <td class="text-dark align-middle text-sm px-5">${item.namaKecamatan}</td>
                                    <td class="text-center align-middle text-center icon-lg px-0">
                                        <a href="/kecamatan/edit/${item.id}">
                                            <i class="fa-solid fa-edit btn-outline-success"
                                            style="margin-right: 5px;"></i>
                                        </a>
                                        <a href="/kecamatan/hapus/${item.id}" id="delete">
                                            <i class="fa-solid fa-trash btn-outline-danger"></i>
                                        </a>
                                    </td>
                                </tr>`;
                                        break;

                                    case 'kelurahan':
                                        row = `
                                <tr>
                                    <td class="text-dark text-center align-middle text-sm">${index + 1}</td>
                                    <td class="text-dark align-middle text-sm px-5">${item.namaKelurahan}</td>
                                    <td class="text-dark align-middle text-sm px-5">${item.kecamatan ? item.kecamatan.namaKecamatan : ''}</td>
                                    <td class="text-center align-middle text-center icon-lg px-0">
                                        <a href="/kelurahan/edit/${item.id}">
                                            <i class="fa-solid fa-edit btn-outline-success"
                                            style="margin-right: 5px;"></i>
                                        </a>
                                        <a href="/kelurahan/hapus/${item.id}" id="delete">
                                            <i class="fa-solid fa-trash btn-outline-danger"></i>
                                        </a>
                                    </td>
                                </tr>`;
                                        break;

                                    default:
                                        row = `
                                <tr>
                                    <td colspan="4">Data tidak ditemukan untuk tipe ini.</td>
                                </tr>`;
                                }

                                tableBody.append(row);
                            });
                        } else {
                            // Jika tidak ada data ditemukan
                            tableBody.append(
                                '<tr><td colspan="3">Tidak ada data ditemukan</td></tr>');
                        }
                    },
                    error: function() {
                        console.error('Terjadi kesalahan dalam pencarian');
                    }
                });
            });
        });
    </script> --}}


    {{-- <script>
        $(document).ready(function() {
            // Event keyup untuk input pencarian global
            $('#globalSearchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();

                // Loop melalui semua tabel dalam konten
                $('table').each(function() {
                    $(this).find('tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(
                            value) > -1)
                    });
                });
            });
            // $('#searchInput').on('keyup', function() {
            //     var value = $(this).val().toLowerCase(); // Ambil input pengguna dan ubah ke huruf kecil
            //     $('#dataTable tbody tr').filter(function() {
            //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -
            //             1); // Sembunyikan baris yang tidak cocok
            //     });
            // });
        });
    </script> --}}


    <!-- Argon Dashboard Scripts -->
    <script src="{{ asset('argon-dashboard/js/argon-dashboard.min.js') }}"></script>

    @yield('scripts')

</body>

</html>
