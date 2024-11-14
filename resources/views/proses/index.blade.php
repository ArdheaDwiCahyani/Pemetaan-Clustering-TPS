@extends('layouts.app')

@section('title', 'Proses Clustering')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-group row align-items-center">
                            <label class="col-auto col-form-label mb-0 me-2">Tahun</label>
                            <div class="col-auto">
                                <input type="text" class="form-control" placeholder="Pilih Tahun"
                                    style="max-width: 150px;">
                            </div>
                            <div class="col-auto ms-auto">
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-outline-primary" style="min-width: 120px;">Proses</a>
                                    <a href="#" class="btn btn-danger" style="min-width: 120px;">Pemetaan</a>
                                    <a href="#" class="btn btn-primary" style="min-width: 120px;">Export Data</a>
                                </div>
                            </div>
                        </div>
                        {{-- @foreach ($result['clusters'] as $clusterIndex => $cluster) --}}
                        {{-- <h4>
                                Cluster {{ $clusterIndex + 1 }} (Prioritas: {{ $clusterIndex == 0 ? 'Tinggi' : ($clusterIndex == 1 ? 'Sedang' : 'Rendah') }})
                            </h4> --}}

                        @foreach ($groupedByCluster as $clusterIndex => $clusterData)
                            <h3>Cluster {{ $clusterIndex + 1 }}</h3>
                            <div class="table-responsive p-0">
                                <table class="table table align-items-center mb-0" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px;"
                                                class="text-dark text-center text-sm font-weight-medium">
                                                No</th>
                                            <th class="text-dark text-sm font-weight-medium px-0">Nama TPS</th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Volume Sampah
                                                <br>(Ton)
                                            </th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Jarak ke TPA
                                                <br>(km)
                                            </th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Rata-Rata Jarak
                                                <br>(km)
                                            </th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Cluster</th>
                                            <th class="text-dark text-sm text-center font-weight-medium">Prioritas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($no = 1)
                                        @foreach ($clusterData as $data)
                                            <tr>
                                                <td class="text-dark text-center align-middle text-sm">{{ $no++ }}
                                                </td>
                                                <td class="text-dark align-middle text-sm text-wrap">{{ $data['namaTPS'] }}
                                                </td>
                                                <td class="text-dark text-center align-middle text-sm">{{ $data['volume'] }}
                                                </td>
                                                <td class="text-dark text-center align-middle text-sm">{{ $data['jarak'] }}
                                                </td>
                                                <td class="text-dark text-center align-middle text-sm">
                                                    {{ $data['rata_rata_jarak'] }}</td>
                                                <td class="text-dark text-center align-middle text-sm">Cluster
                                                    {{ $data['cluster'] + 1 }}</td>
                                                <td class="text-dark text-center align-middle text-sm">
                                                    {{ $data['prioritas'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach

                        {{-- @endforeach --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
