@extends('layouts.app')

@section('title', 'Form Tambah Jarak')

@section('content')
    <form action="{{ route('jarak.tambah.simpan') }} " method="post">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body2 mb-0">
                            <div class="form-group mb-4">
                                <label for="tps_asal_id" class="text-dark text-sm font-weight-medium">TPS Asal</label>
                                <select name="tps_asal_id" id="tps_asal_id" class="form-control" required>
                                    <option value="" disabled selected>--- Pilih TPS Asal ---</option>
                                    @foreach ($tps as $row)
                                        <option value="{{ $row->id }}">{{ $row->namaTPS }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label for="tps_tujuan_id" class="text-dark text-sm font-weight-medium">TPS Tujuan</label>
                                <select name="tps_tujuan_id" id="tps_tujuan_id" class="form-control" required>
                                    <option value="" disabled selected>--- Pilih TPS Tujuan ---</option>
                                    @foreach ($tps as $row)
                                        <option value="{{ $row->id }}">{{ $row->namaTPS }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label for="jarak" class="text-dark text-sm font-weight-medium">Jarak (km)</label>
                                <input type="number" name="jarak" id="jarak" class="form-control" placeholder="Masukkan Jarak" required step="0.01" min="0">
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
