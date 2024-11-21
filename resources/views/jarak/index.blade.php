@extends ('layouts.app')

@section('title', 'Data Jarak Antar TPS')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-3" style="gap: 15px;">
                            <form method="GET" action="{{ route('jarak') }}" class="d-flex w-100" style="gap: 15px;"
                                id="filterForm">
                                <div class="d-flex flex-column" style="gap: 5px;">
                                    <label for="tps_asal" class="form-label text-dark text-sm font-weight-medium mb-1">Pilih
                                        TPS Asal</label>
                                    <select name="tps_asal" id="tps_asal" class="form-select"
                                        style="width: 290px; height: 40px;" onchange="submitForm()">
                                        <option value="">-- Semua TPS --</option>
                                        @foreach ($tpsList as $tps)
                                            <option value="{{ $tps->id }}"
                                                {{ $tpsAsalId == $tps->id ? 'selected' : '' }}>
                                                {{ $tps->namaTPS }}
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
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                        </option>
                                    </select>
                                </div>
                            </form>

                            <!-- Tombol Aksi -->
                            <div class="d-flex align-items-end" style="gap: 5px;">
                                <a href="{{ route('jarak.tambah') }}" class="btn btn-outline-primary">Tambah</a>
                                <a href="{{ route('jarak.import') }}" class="btn btn-warning">Import</a>
                                <a class="btn btn-primary" href="#">Export</a>
                            </div>
                        </div>
                        <div class="table-responsive p-0">
                            <table class="table table align-items-center mb-0 ">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;" class="text-dark text-center text-sm font-weight-medium">
                                            No</th>
                                        <th class="text-dark text-sm font-weight-medium px-5">TPS Asal</th>
                                        <th class="text-dark text-sm font-weight-medium px-6">TPS Tujuan</th>
                                        <th style="width: 200px",
                                            class="text-center text-dark text-sm font-weight-medium px-6">Jarak (km)</th>
                                        <th style="width: 200px", class="text-center text-dark text-sm font-weight-medium">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($no = 1)
                                    @foreach ($jarak as $row)
                                        <tr>
                                            <td class="text-dark text-center align-middle text-sm">{{ $no++ }}</td>
                                            <td class="text-dark align-middle text-sm px-5">{{ $row->tpsAsal->namaTPS }}
                                            </td>
                                            <td class="text-dark align-middle text-sm px-6">{{ $row->tpsTujuan->namaTPS }}
                                            </td>
                                            <td class="text-center text-dark align-middle text-sm px-6">{{ $row->jarak }}
                                            </td>
                                            <td class="text-dark text-center align-middle text-center icon-lg">
                                                <a href="{{ route('jarak.edit', $row->id) }}">
                                                    <i class="fa-solid fa-edit btn-outline-success"
                                                        style="margin-right: 5px;"></i>
                                                </a>
                                                <a href="{{ route('jarak.hapus', $row->id) }}" id="delete">
                                                    <i class="fa-solid fa-trash btn-outline-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $jarak->appends(request()->input())->links() }}
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
