@extends('layouts.app')

@section('content')
    <form action="{{ route('sampah.tambah.update', $sampah->id) }} " method="post">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body2 mb-0">
                            <!-- Pesan Error untuk Kombinasi tps_id dan Tahun -->
                            @if ($errors->has('tps_tahun_exists'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('tps_tahun_exists') }}
                                </div>
                            @endif
                            <div class="form-group mb-4">
                                <label for="tps_id" class="text-dark text-sm font-weight-medium">Nama TPS</label>
                                <select name="tps_id" id="tps_id" class="form-control choices-single" required>
                                    <option value="" disabled selected>-- Pilih TPS --</option>
                                    @foreach ($tps as $row)
                                        <option value="{{ $row->id }}"
                                            {{ $row->id == $sampah->tps_id ? 'selected' : '' }}>
                                            {{ $row->namaTPS }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label for="tahun" class="text-dark text-sm font-weight-medium">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" min="2000"
                                    max="2099" value="{{ isset($sampah) ? $sampah->tahun : '' }}">
                            </div>
                            <div class="form-group mb-4">
                                <label for="volumeSampah" class="text-dark text-sm font-weight-medium">Volume Sampah
                                    (Ton)</label>
                                <input type="number" name="volumeSampah" id="volumeSampah"
                                    class="form-control choices-single" value="{{ isset($sampah) ? $sampah->volumeSampah : '' }}" required>
                                </input>
                            </div>
                        </div>
                        <div class="card-footer mt-0">
                            <button type="submit" class="btn btn-primary bs-btn-active-bg">Simpan</button>
                            <a href="{{ route('sampah') }}" class="btn btn-outline-primary ms-1">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
