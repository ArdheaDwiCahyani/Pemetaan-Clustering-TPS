@extends ('layouts.app')

@section('title', 'Data TPS')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-end mb-3" style="gap: 15px;">
                            <!-- Form Filter -->
                            <form method="GET" action="{{ route('tps') }}" class="d-flex w-100" style="gap: 15px;"
                                id="filterForm">
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
                                <a href="{{ route('tps.tambah') }}" class="btn btn-outline-primary">Tambah</a>
                                <a href="{{ route('tps.import') }}" class="btn btn-warning">Import</a>
                                <a class="btn btn-primary" href="#">Export</a>
                            </div>
                        </div>
                        <div class="table-responsive p-0">
                            <table class="table table align-items-center mb-0 " cellspacing="0">
                                <thead>
                                    <tr>
                                        <th style="width: 120px;" class="text-dark text-center text-sm font-weight-medium">
                                            No
                                        </th>
                                        <th style="width: 250px" class="text-dark text-sm font-weight-medium px-2">Nama
                                            TPS</th>
                                        <th style="width: 150px" class="text-dark text-sm font-weight-medium px-0">
                                            Kelurahan</th>
                                        @foreach ($parameter as $row)
                                            @if ($row->namaParameter == 'Jarak ke TPA')
                                                <th class="text-dark text-sm text-center font-weight-medium px-0">
                                                    {{ $row->namaParameter }} <br>(Km)
                                                    <!-- Dinamis kolom untuk parameter -->
                                                </th>
                                            @endif
                                        @endforeach
                                        <th class="text-center text-dark text-sm font-weight-medium">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($no = ($tps->currentPage() - 1) * $tps->perPage() + 1)
                                    @foreach ($tps as $index => $row)
                                        <tr>
                                            <td class="text-dark text-center align-middle text-sm">{{ $no++ }}</td>
                                            <td style="width: 200px;" class="text-dark align-middle text-sm text-wrap px-2">
                                                {{ $row->namaTPS }}</td>
                                            <td class="text-dark align-middle text-sm px-0">
                                                {{ $row->kelurahan->namaKelurahan }}</td>
                                            @foreach ($parameter as $param)
                                                @if ($param->namaParameter == 'Jarak ke TPA')
                                                    <td class="text-dark text-center align-middle text-sm">
                                                        {{ $row->parameter->where('pivot.entity', 'tps')->first()->pivot->nilai_parameter ?? 'N/A' }}
                                                    </td>
                                                @endif
                                            @endforeach
                                            <td class="text-center align-middle text-center icon-lg">
                                                <a href="{{ route('tps.edit', $row->id) }}">
                                                    <i class="fa-solid fa-edit text-success" style="margin-right: 5px;"></i>
                                                </a>
                                                <a href="{{ route('tps.hapus', $row->id) }}" id="delete">
                                                    <i class="fa-solid fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $tps->appends(request()->input())->links() }}
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
