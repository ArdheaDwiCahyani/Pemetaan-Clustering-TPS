@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            {{-- menambahkan tahun --}}
                            <form action="" method="POST" id="tambah_tahun"
                                class="d-flex align-items-center mb-2 mb-sm-0">
                                @csrf
                                <div class="form-group mb-0 me-2 flex-grow-1">
                                    <input type="text" name="tahun" id="tahun" class="form-control" required
                                        placeholder="Masukkan Tahun">
                                </div>
                                <button type="submit" class="btn btn-primary d-flex align-items-center mb-2 mb-sm-0">Tambah
                                    Tahun</button>
                            </form>
                            {{-- button --}}
                            <div class="d-flex">
                                <a id="add-btn" class="btn btn-outline-primary bs-btn-active-bg mb-3 ms-1"
                                    href="#">Tambah</a>
                                <a id="import-btn" class="btn btn-warning bs-btn-active-bg mb-3 ms-1"
                                    href="#">Import</a>
                                <a id="export-btn" class="btn btn-primary bs-btn-active-bg mb-3 ms-1"
                                    href="#">Export</a>
                            </div>
                        </div>

                        {{-- dropdown tahun --}}
                        <div class="d-flex flex-column mb-3">
                            <div class="d-flex flex-column flex-sm-row mb-0">
                                <div class="me-sm-2 mb-2 mb-sm-0" style="width: 21%">
                                    <div class="form-group">
                                        <label for="tahun"
                                            class="form-label text-dark text-sm font-weight-normal mb-2">Pilih
                                            Tahun</label>
                                        <select name="tahun" id="tahun" class="form-select" required
                                            onchange="autoSubmit()">
                                            <option value="">Semua Tahun</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Hapus Tahun Terpilih akan muncul di bawah form jika ada tahun yang dipilih -->
                            <div class="mt-0">
                                <button type="submit" id="remove-year-btn" style="display: none;" onclick="removeYear()"
                                    class="btn btn-danger">Hapus Tahun Terpilih</button>
                            </div>

                        </div>

                        <!-- Tabel Data Sampah -->
                        <div class="table-responsive p-0">
                            <table class="table table-striped table-hover" id="data-table-sampah">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let dataTable;
        // Fungsi untuk menyimpan tahun ke localStorage
        document.getElementById('tambah_tahun').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman
            const tahunInput = document.getElementById('tahun').value; //mengambil nilai input tahun
            if (tahunInput) {
                let tahunList = JSON.parse(localStorage.getItem('tahunList')) ||
            []; //mengambil daftar tahun dari localstorage
                if (!tahunList.includes(tahunInput)) { //Mengecek apakah tahunInput belum ada dalam daftar tahunList
                    tahunList.push(tahunInput); //tambahkan tahun input ke tahun list
                    localStorage.setItem('tahunList', JSON.stringify(
                        tahunList
                        )); // Menyimpan kembali tahunList ke dalam localStorage setelah diubah menjadi format JSON

                    // Gunakan SweetAlert untuk notifikasi sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Tahun berhasil ditambahkan ke daftar.',
                        confirmButtonText: 'OK'
                    });

                    populateSelect(); // Memperbarui select setelah menambah
                } else {
                    // Gunakan SweetAlert untuk notifikasi duplikat
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Tahun sudah ada di daftar.',
                        confirmButtonText: 'OK'
                    });
                }
                document.getElementById('tahun').value = ''; // Reset kolom input
            }
        });

        // Fungsi untuk mengisi elemen select dengan data dari DataTable dan localStorage
        function populateSelect() {
            // Mengambil elemen <select> dengan id="tahun", yang nantinya akan diisi dengan daftar tahun
            const selectElement = document.querySelector('select#tahun');
            // ambil tahun dari local storage
            const tahunList = JSON.parse(localStorage.getItem('tahunList')) || [];

            // Lakukan fetch data dari allSampah
            fetch("{{ route('allSampah') }}")
                .then(response => response.json()) // Mengonversi hasil dari API ke dalam format JSON
                .then(data => {
                    const tahunFromApi = [...new Set(data.map(item => item.tahun))]; // ekstraksi tahun dari API
                    const allYears = [...new Set([...tahunFromApi, ...
                        tahunList
                    ])]; // gabung data dari API dan local storage

                    selectElement.innerHTML =
                        '<option value="">Semua Tahun</option>'; // Mengatur ulang isi <select> dengan opsi default "Semua Tahun"
                    // Melakukan iterasi pada daftar tahun
                    allYears.forEach(tahun => {
                        const option = document.createElement('option');
                        option.value = tahun;
                        option.textContent = tahun;
                        selectElement.appendChild(option); //Menambahkan opsi ke dalam <select>
                    });

                    // Memuat Tahun yang Terakhir Dipilih
                    loadSelectedYear();
                })
                .catch(error => console.error("Error fetching tahun data:", error));
        }

        // Fungsi untuk memfilter data di DataTable berdasarkan tahun
        function autoSubmit() {
            const selectElement = document.querySelector(
            'select#tahun'); // Mengambil elemen <select> yang memiliki id="tahun"
            const selectedYear = selectElement.value
        .trim(); // Mengambil nilai tahun yang dipilih, lalu trim() digunakan untuk menghapus spasi di awal dan akhir

            // Simpan tahun yang dipilih ke localStorage
            if (selectedYear) {
                localStorage.setItem('selectedYear', selectedYear);
                console.log('Selected year saved to localStorage:', selectedYear);
            } else {
                localStorage.removeItem('selectedYear'); // Hapus jika tidak ada tahun yang dipilih
                console.log('Year selection cleared from localStorage');
            }

            // Terapkan filter pada DataTable
            if (selectedYear) {
                dataTable.columns(2).search(selectedYear).draw();
            } else {
                dataTable.columns(2).search('').draw(); // Reset filter jika tidak ada tahun yang terpilih
            }
        }

        // fungsi untuk mengambil tahun terakhir yang dipilih dari localstorage
        function loadSelectedYear() {
            const selectElement = document.querySelector('select#tahun');
            const savedYear = localStorage.getItem('selectedYear'); // Mengambil nilai tahun yang tersimpan di localStorage

            if (savedYear) {
                selectElement.value = savedYear;
                console.log('Loaded selected year from localStorage:', savedYear);

                // Terapkan filter DataTable berdasarkan tahun yang dimuat
                dataTable.columns(2).search(savedYear).draw();
            } else {
                console.log('No saved year in localStorage');
            }
        }

        //mengirim link a href secara dinamis
        document.addEventListener("DOMContentLoaded", function() {

            // Ambil tombol berdasarkan ID atau kelas
            const importBtn = document.getElementById('import-btn');
            const addBtn = document.getElementById('add-btn');
            const exportBtn = document.getElementById('export-btn');

            // Ambil tahun yang dipilih dari localStorage
            const selectedYear = localStorage.getItem('selectedYear');

            if (selectedYear) {

                // Setel href dinamis berdasarkan nilai tahun dari localStorage untuk tombol import
                if (importBtn) {
                    importBtn.href = "{{ route('sampah.import', ['tahun' => '']) }}" + selectedYear;
                }

                // Setel href dinamis untuk tombol tambah (misalnya menuju halaman tambah berdasarkan tahun)
                if (addBtn) {
                    addBtn.href = "{{ route('sampah.tambah', ['tahun' => '']) }}" + selectedYear;
                }

                // Setel href dinamis untuk tombol export (misalnya menuju halaman export berdasarkan tahun)
                if (exportBtn) {
                    exportBtn.href = "{{ route('sampah.export', ['tahun' => '']) }}" + selectedYear;
                }
            }
        });

        // Fungsi untuk menghapus tahun dari localStorage
        function removeYear() {
            // Mengambil nilai tahun yang saat ini dipilih dalam elemen <select> dengan id="tahun"
            const selectedYear = document.querySelector('select#tahun').value;
            let tahunList = JSON.parse(localStorage.getItem('tahunList')) || [];

            if (tahunList.includes(
                selectedYear)) { // cek apakah tahun yang dipilih (selectedYear) ada di dalam array tahunList
                // Menghapus Tahun dari tahunList
                tahunList = tahunList.filter(tahun => tahun !== selectedYear);
                localStorage.setItem('tahunList', JSON.stringify(tahunList));

                // SweetAlert pemberitahuan sukses
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Tahun berhasil dihapus dari daftar.',
                    confirmButtonText: 'OK'
                });

                populateSelect(); // Memperbarui daftar tahun select setelah menghapus
                autoSubmit(); // Memastikan DataTable diperbarui setelah tahun dihapus
            } else {
                // SweetAlert jika tahun tidak ditemukan di daftar
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tahun tidak ditemukan di daftar.',
                    confirmButtonText: 'OK'
                });
            }
        }

        // fungsi untuk mengatur visibilitas button hapus tahun terpilih
        function setupRemoveButtonVisibility() {
            const removeButton = document.getElementById('remove-year-btn');

            // Pantau perubahan dalam DataTable
            dataTable.on('draw', function() {
                const selectedYear = document.querySelector('select#tahun').value.trim();
                if (selectedYear && dataTable.rows({
                        filter: 'applied'
                    }).data().length === 0) {
                    // Tampilkan tombol hapus jika tidak ada data yang sesuai
                    removeButton.style.display = 'block';
                } else {
                    // Sembunyikan tombol hapus jika ada data yang sesuai atau tidak ada filter
                    removeButton.style.display = 'none';
                }
            });
        }

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
                scriptBootstrap.src =
                    "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js";
                scriptBootstrap.integrity =
                    "sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM";
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
                dataTable = $('#data-table-sampah').DataTable({
                    processing: true,
                    responsive: true,
                    autoWidth: false,
                    paging: true,
                    ajax: {
                        url: "{{ route('allSampah') }}",
                        type: "GET",
                        dataSrc: function(json) {
                            return json.map((item, index) => {
                                item.no = index + 1;
                                return item;
                            });
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
                            className: 'text-center text-dark align-middle text-sm w-7'
                        },
                        {
                            data: "nama_tps",
                            title: 'Nama TPS',
                            className: 'text-start text-dark align-middle text-sm text-wrap'
                        },
                        {
                            data: 'tahun',
                            title: 'tahun',
                            className: 'text-center text-dark align-middle text-sm text-wrap w-10'
                        },
                        {
                            data: 'volume_sampah',
                            title: 'Volume Sampah (Ton)',
                            className: 'text-center text-dark align-middle text-sm text-wrap'
                        },
                        {
                            data: 'jarak_tpa',
                            title: 'Jarak ke TPA (Km)',
                            className: 'text-center text-dark align-middle text-sm text-wrap'
                        },
                        {
                            data: 'rata_rata_jarak',
                            title: 'Rata-Rata Jarak (Km)',
                            className: 'text-center text-dark align-middle text-sm text-wrap'
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
                            <a href="{{ route('sampah.edit', ':id') }}">
                                <i class="fa-solid fa-edit btn-outline-success" style="margin-right: 5px;"></i>
                            </a>
                            <a href="{{ route('sampah.hapus', ':id') }}" id="delete">
                                <i class="fa-solid fa-trash btn-outline-danger"></i>
                            </a>
                        </div>
                    `.replace(/:id/g, row.id);
                            }
                        }
                    ]
                });

                // Panggil populateSelect setiap kali data dari server selesai dimuat
                $('#data-table-sampah').on('xhr.dt', function() {
                    populateSelect();
                });

                $('.dataTables_filter label, .dataTables_length label, .dataTables_info label').addClass(
                    'text-sm font-weight-medium text-dark');
                $('.dataTables_filter input, .dataTables_length select').addClass(
                    'text-sm font-weight-medium text-dark');

                // Pasang listener untuk tombol hapus
                setupRemoveButtonVisibility();
            });

        }
        loadDataTableScript(initializeDataTable);
    </script>
@endsection
