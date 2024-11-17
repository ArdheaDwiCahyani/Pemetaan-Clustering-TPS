@extends('layouts.app')

@section('title', 'Form Edit Data')

@section('content')
    <form action="{{ route('tps.tambah.update', $tps->id) }}" method="post">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body2">
                            <div class="form-group mb-4">
                                <label for="namaTPS" class="text-dark text-sm font-weight-medium">Nama</label>
                                <input type="text" class="form-control" id="namaTPS" name="namaTPS"
                                    value="{{ isset($tps) ? $tps->namaTPS : '' }}">
                            </div>
                            <div class="form-group mb-4">
                                <label for="kelurahans_id" class="text-dark text-sm font-weight-medium">Kelurahan</label>
                                <select class="form-control" name="kelurahans_id" id="kelurahans_id">
                                    <option value="" disabled selected>--- Pilih kelurahan ---</option>
                                    @foreach ($kelurahan as $row)
                                        <option value="{{ $row->id }}"
                                            {{ $row->id == $tps->kelurahans_id ? 'selected' : '' }}>
                                            {{ $row->namaKelurahan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                @foreach ($parameter as $param)
                                    @if ($param->namaParameter == 'Jarak ke TPA')
                                        <div class="form-group mb-4">
                                            <label for="param{{ $param->id }}"
                                                class="text-dark text-sm font-weight-medium">{{ $param->namaParameter }}</label>
                                            <input type="hidden" name="params_id[]" value="{{ $param->id }}">
                                            {{-- Mengambil nilai_parameter dari hubungan pivot berdasarkan params_id --}}
                                            <input type="number" name="nilai_parameter[]" id="param{{ $param->id }}"
                                                class="form-control"
                                                value="{{ $tps->parameter->where('id', $param->id)->first()->pivot->nilai_parameter ?? '' }}"
                                                required step="0.01">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="form-group mb-4">
                                <label for="longitude" class="text-dark text-sm font-weight-medium">Koordinat Longitude (X)</label>
                                <input type="number" class="form-control" id="longitude" name="longitude" step="any"
                                    placeholder="Masukkan Koordinat Longitude"
                                    value="{{ isset($tps) ? $tps->longitude : '' }}">
                            </div>
                            <div class="form-group mb-4">
                                <label for="latitude" class="text-dark text-sm font-weight-medium">Koordinat Latitude (Y)</label>
                                <input type="number" class="form-control" id="latitude" name="latitude" step="any"
                                    placeholder="Masukkan Koordinat Latitude"
                                    value="{{ isset($tps) ? $tps->latitude : '' }}">
                            </div>
                        </div>
                        <div class="card-footer mt-0">
                            <button type="submit" class="btn btn-primary bs-btn-active-bg">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
