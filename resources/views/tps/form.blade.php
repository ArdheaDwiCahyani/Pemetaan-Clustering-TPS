@extends('layouts.app')

@section('title', 'Form Tambah Data')

@section('content')
    <form action="{{ route('tps.tambah.simpan') }} " method="post">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body2 mb-0">
                            <div class="form-group mb-4">
                                <label for="namaTPS" class="text-dark text-sm font-weight-medium">Nama TPS</label>
                                <input type="text" class="form-control" id="namaTPS" name="namaTPS"
                                    placeholder="Masukkan Nama TPS">
                            </div>
                            <div class="form-group mb-4">
                                <label for="kelurahans_id" class="text-dark text-sm font-weight-medium">Kelurahan</label>
                                <select class="form-control" name="kelurahans_id" id="kelurahans_id">
                                    <option value="" disabled selected>--- Pilih Kelurahan ---</option>
                                    @foreach ($kelurahan as $row)
                                        <option value="{{ $row->id }}">{{ $row->namaKelurahan }}</option>
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
                                            <input type="number" name="nilai_parameter[]" id='param{{ $param->id }}'
                                                class="form-control" placeholder="Masukkan Nilai" required step="0.01">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="form-group mb-4">
                                <label for="longitude" class="text-dark text-sm font-weight-medium">Koordinat Longitude (X)</label>
                                <input type="number" class="form-control" id="longitude" name="longitude" step="any"
                                    placeholder="Masukkan Koordinat Longitude">
                            </div>
                            <div class="form-group mb-4">
                                <label for="latitude" class="text-dark text-sm font-weight-medium">Koordinat Latitude (Y)</label>
                                <input type="number" class="form-control" id="latitude" name="latitude" step="any"
                                    placeholder="Masukkan Koordinat Latitude">
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
