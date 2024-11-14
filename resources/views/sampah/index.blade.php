@extends ('layouts.app')

@section('title', 'Data Sampah')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body ">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <a href="{{ route('sampah.tambah') }}" class="btn btn-outline-primary mb-3 mr-2"> Tambah Data
                                </a>
                            </div>
                            <div>
                                <a class="btn btn-warning bs-btn-active-bg mb-3" href="{{ route('sampah.import') }}">Import
                                    Data</a>
                                <a class="btn btn-primary bs-btn-active-bg mb-3" href="#">Export Data</a>
                            </div>
                        </div>
                        <div class="table-responsive p-0">
                            <table class="table table align-items-center mb-0 " cellspacing="0">
                                <thead>
                                    <tr>
                                        <th style="width: 70px" class="text-dark text-center text-sm font-weight-medium">
                                            No
                                        </th>
                                        <th style="width: 200px" class="text-dark text-sm font-weight-medium px-2">Nama
                                            TPS</th>
                                        <th style="width: 80px"
                                            class="text-center text-dark text-sm font-weight-medium px-0">Tahun
                                        </th>
                                        <th class="text-dark text-sm font-weight-medium px-0 text-wrap-center">Volume
                                            Sampah <br>(ton)</th>
                                        <th class="text-dark text-sm font-weight-medium px-0 text-wrap-center">Jarak ke
                                            TPA <br>(km)</th>
                                        <th class="text-dark text-sm font-weight-medium px-0 text-wrap-center">Rata-Rata
                                            Jarak <br>(km)</th>
                                        <th style="width: 100px"
                                            class="text-center text-dark text-sm font-weight-medium px-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($no = 1)
                                    @foreach ($sampah as $row)
                                        <tr>
                                            <td class="text-dark text-center align-middle text-sm">{{ $no++ }}</td>
                                            <td style="width: 200px;" class="text-dark align-middle text-sm text-wrap px-2">
                                                {{ $row->tps->namaTPS }}</td>
                                            <td class="text-center text-dark align-middle text-sm px-0">
                                                {{ $row->tahun }}</td>
                                            <td class="text-dark text-center align-middle text-sm">
                                                {{ $row->tps->parameter->where('pivot.entity', 'sampah')->first()->pivot->nilai_parameter ?? '' }}
                                            </td>
                                            <td class="text-dark text-center align-middle text-sm">
                                                {{ $row->jarak_tpa ?? '' }}
                                                <!-- Menggunakan accessor yang sudah didefinisikan -->
                                            </td>
                                            <td class="text-dark text-center align-middle text-sm">
                                                {{ $row->rata_rata_jarak ?? '' }}
                                                <!-- Menggunakan accessor yang sudah didefinisikan -->
                                            </td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
