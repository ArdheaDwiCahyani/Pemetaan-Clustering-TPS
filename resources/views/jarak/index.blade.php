@extends ('layouts.app')

@section('title', 'Data Jarak Antar TPS')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <a href="{{ route('jarak.tambah') }}" class="btn btn-outline-primary mb-3 mr-2"> Tambah Jarak
                                </a>
                            </div>
                            <div>
                                <a class="btn btn-warning bs-btn-active-bg mb-3" href="{{ route('jarak.import') }}">Import Data</a>
                                <a class="btn btn-primary bs-btn-active-bg mb-3" href="#">Export Data</a>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
