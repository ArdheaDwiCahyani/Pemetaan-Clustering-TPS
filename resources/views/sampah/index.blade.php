@extends('layouts.app')

@section('title', 'Data Sampah')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <form action="{{ route('sampah.addTahun') }}" method="POST"
                                class="d-flex align-items-center mb-2 mb-sm-0">
                                @csrf
                                <div class="form-group mb-0 me-2 flex-grow-1">
                                    <input type="text" name="tahun" id="tahun" class="form-control" required
                                        placeholder="Masukkan Tahun">
                                </div>
                                <button type="submit" class="btn btn-primary d-flex align-items-center mb-2 mb-sm-0">Tambah
                                    Tahun</button>
                            </form>
                            <div class="d-flex">
                                <a href="{{ route('sampah.tambah', ['tahun' => request('tahun')]) }}"
                                    class="btn btn-outline-primary mb-3 ms-1">Tambah</a>
                                <a class="btn btn-warning bs-btn-active-bg mb-3 ms-1"
                                    href="{{ route('sampah.import', ['tahun' => request('tahun')]) }}">Import</a>
                                <a class="btn btn-primary bs-btn-active-bg mb-3 ms-1" href="#">Export</a>
                            </div>
                        </div>

                        <div class="d-flex flex-column mb-3">
                            <div class="d-flex flex-column flex-sm-row mb-2">
                                <div class="me-sm-2 mb-2 mb-sm-0">
                                    <form action="{{ route('sampah') }}" method="GET" id="formTahun" class="ml-sm-3">
                                        <div class="form-group">
                                            <label for="tahun"
                                                class="form-label text-dark text-sm font-weight-medium mb-2">Pilih
                                                Tahun</label>
                                            <select name="tahun" id="tahun" class="form-select" required
                                                onchange="autoSubmit()" style="width: 150px">
                                                <option value="">Semua Tahun</option>
                                                @foreach ($tahunList as $tahun)
                                                    <option value="{{ $tahun }}"
                                                        {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                                        {{ $tahun }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div class="mb-2 mb-sm-0">
                                    <form method="GET" action="{{ route('sampah') }}" id="filterForm" class="d-flex mb-0">
                                        <div class="form-group mb-0 me-3">
                                            <label for="per_page"
                                                class="form-label text-dark text-sm font-weight-medium mb-2">Item per
                                                Halaman</label>
                                            <select name="per_page" id="per_page" class="form-select" style="width: 120px;"
                                                onchange="submitForm()">
                                                <option value="4" {{ request('per_page') == 4 ? 'selected' : '' }}>4
                                                </option>
                                                <option value="8" {{ request('per_page') == 8 ? 'selected' : '' }}>8
                                                </option>
                                                <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24
                                                </option>
                                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Tombol Hapus Tahun Terpilih akan muncul di bawah form jika ada tahun yang dipilih -->
                            @if (request('tahun'))
                                @if (!$isDataAvailable)
                                    <div class="mt-0">
                                        <form action="{{ route('sampah.removeTahun') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                                            <button type="submit" class="btn btn-danger">Hapus Tahun Terpilih</button>
                                        </form>
                                    </div>
                                @endif
                            @endif

                        </div>

                        <!-- Tabel Data Sampah -->
                        <div class="table-responsive p-0">
                            <table class="table table align-items-center mb-0" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th style="width: 70px" class="text-dark text-center text-sm font-weight-medium">No
                                        </th>
                                        <th style="width: 200px" class="text-dark text-sm font-weight-medium px-2">Nama TPS
                                        </th>
                                        <th style="width: 80px"
                                            class="text-center text-dark text-sm font-weight-medium px-0">Tahun</th>
                                        <th class="text-dark text-sm font-weight-medium px-0 text-wrap-center">Volume Sampah
                                            <br>(ton)
                                        </th>
                                        <th class="text-dark text-sm font-weight-medium px-0 text-wrap-center">Jarak ke TPA
                                            <br>(km)
                                        </th>
                                        <th class="text-dark text-sm font-weight-medium px-0 text-wrap-center">Rata-Rata
                                            Jarak <br>(km)</th>
                                        <th style="width: 100px"
                                            class="text-center text-dark text-sm font-weight-medium px-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($no = ($sampah->currentPage() - 1) * $sampah->perPage() + 1)
                                    @foreach ($sampah as $row)
                                        <tr>
                                            <td class="text-dark text-center align-middle text-sm">{{ $no++ }}</td>
                                            <td style="width: 200px;" class="text-dark align-middle text-sm text-wrap px-2">
                                                {{ $row->tps->namaTPS }}</td>
                                            <td class="text-center text-dark align-middle text-sm px-0">{{ $row->tahun }}
                                            </td>
                                            <td class="text-dark text-center align-middle text-sm">
                                                {{ $row->tps->parameter->where('pivot.entity', 'sampah')->first()->pivot->nilai_parameter ?? '' }}
                                            </td>
                                            <td class="text-dark text-center align-middle text-sm">
                                                {{ $row->jarak_tpa ?? '' }}</td>
                                            <td class="text-dark text-center align-middle text-sm">
                                                {{ $row->rata_rata_jarak ?? '' }}</td>
                                            <td class="text-center align-middle text-center icon-lg">
                                                <a href="{{ route('sampah.edit', $row->id) }}">
                                                    <i class="fa-solid fa-edit text-success" style="margin-right: 5px;"></i>
                                                </a>
                                                <a href="{{ route('sampah.hapus', $row->id) }}" id="delete">
                                                    <i class="fa-solid fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $sampah->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function autoSubmit() {
            // Mendapatkan form berdasarkan ID
            document.getElementById("formTahun").submit();
        }

        function submitForm() {
            document.getElementById('filterForm').submit();
        }
    </script>
@endsection
