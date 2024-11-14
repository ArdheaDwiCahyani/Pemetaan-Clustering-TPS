@extends('layouts.app')

@section('title', 'Form Tambah Data')

@section('content')
    <form action="{{ route('sampah.tambah.simpan') }} " method="post">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body2 mb-0">
                            <div class="form-group mb-4">
                                <label for="tps_id" class="text-dark text-sm font-weight-medium">Nama TPS</label>
                                <select name="tps_id" id="tps_id" class="form-control" required>
                                    <option value="" disabled selected>--- Pilih TPS ---</option>
                                    @foreach ($tps as $row)
                                        <option value="{{ $row->id }}">{{ $row->namaTPS }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label for="tahun" class="text-dark text-sm font-weight-medium">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" min="2000"
                                    max="2099" placeholder="Masukkan Tahun">
                            </div>
                            @foreach ($parameter as $param)
                                @if ($param->namaParameter == 'Volume Sampah')
                                    <div class="form-group mb-4">
                                        <label for="volume_sampah_{{ $param->id }}"
                                            class="text-dark text-sm font-weight-medium">{{ $param->namaParameter }}</label>
                                        <input type="number" name="volume_sampah[{{ $param->id }}]"
                                            id="volume_sampah[{{ $param->id }}]" class="form-control"
                                            placeholder="Masukkan Volume Sampah" step="any" required>
                                    </div>
                                @endif
                            @endforeach
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
