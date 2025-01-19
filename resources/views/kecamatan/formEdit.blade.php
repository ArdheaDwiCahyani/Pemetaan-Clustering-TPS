@extends('layouts.app')

@section('content')
    <form action="{{ route('kecamatan.tambah.update', $kecamatan->id) }}" method="post" id="myForm">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <label for="namaKecamatan" class="text-dark text-sm font-weight-medium">Nama</label>
                                <input type="text" class="form-control" id="namaKecamatan" name="namaKecamatan"
                                    value="{{ isset($kecamatan) ? $kecamatan->namaKecamatan : '' }}">
                                <small id="errorNamaKecamatan" class="text-danger" style="display: none;">
                                    Kolom tidak boleh kosong!
                                </small>
                            </div>
                        </div>
                        <div class="card-footer mt-0">
                            <button type="submit" class="btn btn-primary bs-btn-active-bg">Simpan</button>
                            <a href="{{ route('kecamatan') }}" class="btn btn-outline-primary ms-1">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
