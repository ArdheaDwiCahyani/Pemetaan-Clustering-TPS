@extends ('layouts.app')

@section('title', 'Data Kelurahan')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-3" style="gap: 15px;">
                            <form method="GET" action="{{ route('kelurahan') }}" class="d-flex w-100" style="gap: 15px;"
                                id="filterForm">
                                <!-- Dropdown Kecamatan -->
                                <div class="d-flex flex-column" style="gap: 5px;">
                                    <label for="kecamatan"
                                        class="form-label text-dark text-sm font-weight-medium mb-1">Pilih Kecamatan</label>
                                    <select name="kecamatan_id" id="kecamatan" class="form-select"
                                        style="width: 220px; height: 40px;" onchange="submitForm()">
                                        <option value="">-- Semua Kecamatan --</option>
                                        @foreach ($kecamatan as $kec)
                                            <option value="{{ $kec->id }}"
                                                {{ request('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                                {{ $kec->namaKecamatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Dropdown Item per Halaman -->
                                <div class="d-flex flex-column" style="gap: 5px;">
                                    <label for="per_page" class="form-label text-dark text-sm font-weight-medium mb-1">Item
                                        per Halaman</label>
                                    <select name="per_page" id="per_page" class="form-select"
                                        style="width: 120px; height: 40px;" onchange="submitForm()">
                                        <option value="4" {{ request('per_page') == 4 ? 'selected' : '' }}>4</option>
                                        <option value="8" {{ request('per_page') == 8 ? 'selected' : '' }}>8</option>
                                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                </div>
                            </form>

                            <!-- Tombol Aksi -->
                            <div class="d-flex align-items-end" style="gap: 5px;">
                                <a href="{{ route('kelurahan.tambah') }}" class="btn btn-outline-primary">Tambah</a>
                                <a href="{{ route('kelurahan.import') }}" class="btn btn-warning">Import</a>
                                <a class="btn btn-primary" href="#">Export</a>
                            </div>
                        </div>
                        <!-- Tabel Data Kelurahan -->
                        <div class="table-responsive p-0">
                            <table class="table table align-items-center mb-0 " cellspacing="0">
                                <thead>
                                    <tr>
                                        <th style="width: 150px;"
                                            class="text-dark text-center text-sm font-weight-medium px-0">No</th>
                                        <th style="width: 280px" class="text-dark text-sm font-weight-medium px-5">Kelurahan
                                        </th>
                                        <th style="width: 280px" class="text-dark text-sm font-weight-medium px-5">Kecamatan
                                        </th>
                                        <th style="width: 200px"
                                            class="text-center text-center text-dark text-sm font-weight-medium px-0">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($no = ($kelurahan->currentPage() - 1) * $kelurahan->perPage() + 1)
                                    @foreach ($kelurahan as $row)
                                        <tr>
                                            <td class="text-dark text-center align-middle text-sm">{{ $no++ }}</td>
                                            <td class="text-dark align-middle text-sm px-5">{{ $row->namaKelurahan }}</td>
                                            <td class="text-dark align-middle text-sm px-5">
                                                {{ $row->kecamatan->namaKecamatan }}</td>
                                            <td class="text-center align-middle text-center icon-lg px-0">
                                                <a href="{{ route('kelurahan.edit', $row->id) }}">
                                                    <i class="fa-solid fa-edit btn-outline-success"
                                                        style="margin-right: 5px;"></i>
                                                </a>
                                                <a href="{{ route('kelurahan.hapus', $row->id) }}" id="delete">
                                                    <i class="fa-solid fa-trash btn-outline-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Pagination Links -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $kelurahan->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function submitForm() {
            document.getElementById('filterForm').submit();
        }
    </script>
@endsection
