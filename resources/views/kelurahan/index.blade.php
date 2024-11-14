@extends ('layouts.app')

@section('title', 'Data Kelurahan')

{{-- @include('kelurahan.import') --}}

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <a href="{{ route('kelurahan.tambah') }}" class="btn btn-outline-primary mb-3 mr-2"> Tambah
                                    Kelurahan </a>
                            </div>
                            <div>
                                {{-- <a class="btn btn-warning bs-btn-active-bg mb-3" href="{{ route('kelurahan.import') }}">Import Data</a> --}}
                                <a href="{{ route('kelurahan.import') }}" class="btn btn-warning bs-btn-active-bg mb-3">
                                    Import Data
                                </a>
                                <a class="btn btn-primary bs-btn-active-bg mb-3" href="#">Export Data</a>
                            </div>
                        </div>
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
                                    @php($no = 1)
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection
