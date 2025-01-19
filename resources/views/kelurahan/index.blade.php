@extends ('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-3" style="gap: 15px;">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label for="filter-kecamatan"
                                        class="text-dark align-middle text-sm font-weight-normal">Filter
                                        Kecamatan :</label>
                                    <select id="filter-kecamatan" class="form-select" style="width: 100%;">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex align-items-end" style="gap: 5px;">
                                <a href="{{ route('kelurahan.tambah') }}" class="btn btn-outline-primary">Tambah</a>
                                <a href="{{ route('kelurahan.import') }}" class="btn btn-warning">Import</a>
                                <a class="btn btn-primary" href="{{ route('kelurahan.export') }}">Export</a>
                            </div>
                        </div>

                        <!-- Tabel Data Kelurahan -->
                        <div class="table-responsive p-0">
                            <table class="table table-striped table-hover" id="data-table">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk memuat script dan stylesheet secara dinamis
        function loadScripts(callback) {
            // Muat jQuery terlebih dahulu
            var scriptJQuery = document.createElement("script");
            scriptJQuery.src = "https://code.jquery.com/jquery-3.2.1.min.js";
            scriptJQuery.type = "text/javascript";
            scriptJQuery.onload = function() {
                // Setelah jQuery dimuat, muat Select2 CSS
                var linkSelect2 = document.createElement("link");
                linkSelect2.rel = "stylesheet";
                linkSelect2.href = "https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css";
                document.head.appendChild(linkSelect2);

                // Setelah Select2 CSS dimuat, muat Select2 JS
                var scriptSelect2 = document.createElement("script");
                scriptSelect2.src = "https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js";
                scriptSelect2.type = "text/javascript";
                scriptSelect2.onload = function() {
                    // Semua script dan stylesheet sudah dimuat, jalankan callback untuk inisialisasi
                    callback();
                };
                document.head.appendChild(scriptSelect2);
            };
            document.head.appendChild(scriptJQuery);
        }

        // Panggil loadScripts dan inisialisasi Select2 setelah semua script dimuat
        loadScripts(function() {
            $(document).ready(function() {
                $("#filter-kecamatan").select2();
            });
        });
    </script>

    <!-- Tambahkan CSS Kustom untuk DataTables -->
    <style>
        /* Menyesuaikan ukuran dan jenis font untuk Search dan Show Entries */
        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_info label {
            font-size: 1rem;
            font-weight: 500;
            font-family: 'Open Sans', sans-serif;
        }

        /* Menyesuaikan ukuran dan jenis font untuk input Search */
        .dataTables_wrapper .dataTables_filter input {
            font-size: 1rem;
            font-family: 'Open Sans', sans-serif;
            padding: 0.375rem 0.75rem;
            /* Padding sesuai dengan Bootstrap/Argon */
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        /* Menyesuaikan ukuran dan jenis font untuk dropdown Show Entries */
        .dataTables_wrapper .dataTables_length select {
            font-size: 1rem;
            font-family: 'Open Sans', sans-serif;
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container .select2-selection--single {
            font-size: 0.875rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            /* padding: 5px; */
            border: 1px solid #ccc;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            color: #344767;
            font-size: 0.875rem;
            /* font-weight: bold; */
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            color: #344767;
            font-size: 0.875rem;
        }

        .select2-dropdown .select2-results__option {
            font-size: 0.875rem;
        }
    </style>

    <script>
        // Fungsi untuk memuat jQuery dan DataTable hanya saat diperlukan
        function loadDataTableScript(callback) {
            // Cek apakah jQuery sudah dimuat
            if (typeof jQuery == 'undefined') {
                var scriptJQuery = document.createElement("script");
                scriptJQuery.src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js";
                scriptJQuery.type = "text/javascript";
                scriptJQuery.onload = function() {
                    loadDataTableAssets(callback); // Panggil loadAssets setelah jQuery dimuat
                };
                document.head.appendChild(scriptJQuery);
            } else {
                loadDataTableAssets(callback); // Jika jQuery sudah dimuat, langsung panggil loadAssets
            }
        }

        function loadDataTableAssets(callback) {
            // Muat DataTables CSS
            var linkDataTables = document.createElement("link");
            linkDataTables.rel = "stylesheet";
            linkDataTables.href = "https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css";
            document.head.appendChild(linkDataTables);

            // Muat DataTables JS
            var scriptDataTables = document.createElement("script");
            scriptDataTables.src = "https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js";
            scriptDataTables.type = "text/javascript";
            scriptDataTables.onload = function() {
                // Muat Bootstrap JS setelah DataTables JS
                var scriptBootstrap = document.createElement("script");
                scriptBootstrap.src = "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js";
                scriptBootstrap.integrity = "sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM";
                scriptBootstrap.crossOrigin = "anonymous";
                document.head.appendChild(scriptBootstrap);

                // Panggil callback setelah semua resource dimuat
                scriptBootstrap.onload = callback;
            };
            document.head.appendChild(scriptDataTables);
        }

        // Fungsi untuk menginisialisasi DataTable dengan ID #data-table
        function initializeDataTable() {
            $(document).ready(function() {
                const table = $('#data-table').DataTable({
                    processing: true,
                    responsive: true,
                    autoWidth: false,
                    paging: true,
                    deferRender: true,
                    ajax: {
                        "url": "{{ route('allKelurahan') }}",
                        "type": "GET",
                        "dataSrc": function(json) {
                            const kecamatanSet = new Set();
                            json.forEach((item, index) => {
                                item.no = index + 1;
                                kecamatanSet.add(item.kecamatan);
                            });

                            // Tambahkan kecamatan ke dropdown filter
                            const filterSelect = $('#filter-kecamatan');
                            kecamatanSet.forEach(kecamatan => {
                                filterSelect.append(
                                    `<option value="${kecamatan}">${kecamatan}</option>`
                                );
                            });

                            return json;
                        }
                    },
                    language: {
                        paginate: {
                            next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                            previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                        }
                    },
                    columns: [{
                            data: 'no',
                            title: 'No',
                            className: 'text-center text-dark text-sm font-weight-medium'
                        },
                        {
                            data: "namaKelurahan",
                            title: 'Nama Kelurahan',
                            className: 'text-left text-dark text-sm font-weight-medium'
                        },
                        {
                            data: 'kecamatan',
                            title: 'Kecamatan',
                            className: 'text-left text-dark text-sm font-weight-medium'
                            // headerClassName: 'header-cell text-center text-primary font-weight-bold'
                        },
                        {
                            title: "Action",
                            className: 'text-center text-dark text-sm font-weight-medium',
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                            <div class="text-center align-middle px-0" style="font-size: 20px;">
                                <a href="{{ route('kelurahan.edit', ':id') }}">
                                    <i class="fa-solid fa-edit btn-outline-success" style="margin-right: 5px;"></i>
                                </a>
                                <a href="{{ route('kelurahan.hapus', ':id') }}"
                                   id="delete">
                                    <i class="fa-solid fa-trash btn-outline-danger"></i>
                                </a>
                            </div>
                        `.replace(/:id/g, row.id);
                            }
                        }
                    ],                    
                });

                // Tambahkan Kelas Argon ke Elemen Search dan Show Entries setelah DataTable diinisialisasi
                $('.dataTables_filter label, .dataTables_length label, .dataTables_info label').addClass(
                    'text-sm font-weight-medium text-dark');
                $('.dataTables_filter input, .dataTables_length select').addClass(
                    'text-sm font-weight-medium text-dark');


                // Filter berdasarkan kecamatan
                $('#filter-kecamatan').on('change', function() {
                    const filterValue = this.value;
                    if (filterValue) {
                        table.column(2).search(`^${filterValue}$`, true, false).draw();
                    } else {
                        table.column(2).search('').draw();
                    }
                });
            });
        }

        // Panggil fungsi loadDataTableScript untuk memuat semua script yang dibutuhkan, kemudian inisialisasi DataTable
        loadDataTableScript(initializeDataTable);
    </script>
@endsection
