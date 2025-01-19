@extends ('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <a href="{{ route('kecamatan.tambah') }}" class="btn btn-outline-primary mb-4 mr-2"> Tambah Data </a>
                        <div class="table-responsive p-0">
                            <table id="data-table" class="table table-striped table-hover">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- styling js --}}
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
                // menambahkan elemen <script> yang baru saja dibuat ke dalam elemen <head> halaman web
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
                // Menggunakan jQuery DataTable untuk elemen #data-table
                const table = $('#data-table').DataTable({
                    processing: true, //Menampilkan indikator loading saat DataTable sedang mengambil data
                    responsive: true, //Membuat tabel responsif untuk perangkat mobile
                    autoWidth: false, //Mencegah tabel mengatur lebar kolom secara otomatis
                    paging: true, //Mengatifkan fitur pagination
                    ajax: {
                        "url": "{{ route('allKecamatan') }}",
                        "type": "GET",
                        dataSrc: function(json) {
                            return json.map((item, index) => {
                                item.no = index + 1;
                                return item;
                            })
                        }
                    },
                    language: {
                        //Mengganti tombol next dan previous pada pagination dengan ikon Font Awesome
                        paginate: {
                            next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                            previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                        }
                    },
                    columns: [{
                            data: 'no',
                            title: 'No',
                            className: 'text-center text-dark text-sm'
                        },
                        {
                            data: "namaKecamatan",
                            title: 'Nama Kecamatan',
                            className: 'text-left text-dark text-sm'
                        },
                        {
                            title: "Action",
                            className: 'text-center text-dark text-sm',
                            data: null,
                            orderable: false, //Mencegah kolom ini dari dapat diurutkan
                            searchable: false, //Mencegah dari fitur pencarian
                            render: function(data, type, row) {
                                return `
                                <div class="text-center align-middle px-0" style="font-size: 20px;">
                                    <a href="{{ route('kecamatan.edit', ':id') }}">
                                        <i class="fa-solid fa-edit btn-outline-success" style="margin-right: 5px;"></i>
                                    </a>
                                    <a href="{{ route('kecamatan.hapus', ':id') }}"
                                        id="delete">
                                        <i class="fa-solid fa-trash btn-outline-danger"></i>
                                    </a>
                                </div>
                  `.replace(/:id/g, row.id); //Mengganti setiap :id dalam string template dengan row.id
                            }
                        }
                    ],
                });

                // Tambahkan Kelas Argon ke Elemen Search dan Show Entries setelah DataTable diinisialisasi
                $('.dataTables_filter label, .dataTables_length label, .dataTables_info label').addClass(
                    'text-sm font-weight-medium text-dark');
                $('.dataTables_filter input, .dataTables_length select').addClass(
                    'text-sm font-weight-medium text-dark');
            });
        }

        // Panggil fungsi loadDataTableScript untuk memuat semua script yang dibutuhkan, kemudian inisialisasi DataTable
        loadDataTableScript(initializeDataTable);
    </script>
@endsection