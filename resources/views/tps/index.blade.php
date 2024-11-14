@extends ('layouts.app')

@section('title', 'Data TPS')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body ">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <a href="{{ route('tps.tambah') }}" class="btn btn-outline-primary mb-3 mr-2"> Tambah Data
                                </a>
                            </div>
                            <div>
                                <a class="btn btn-warning bs-btn-active-bg mb-3" href="{{ route('tps.import') }}">Import Data</a>
                                <a class="btn btn-primary bs-btn-active-bg mb-3" href="#">Export Data</a>
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
                                                    {{ $row->namaParameter }} <!-- Dinamis kolom untuk parameter -->
                                                </th>
                                            @endif
                                        @endforeach
                                        <th class="text-center text-dark text-sm font-weight-medium">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($no = 1)
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
