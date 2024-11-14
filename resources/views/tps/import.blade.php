@extends('layouts.app')

@section('content')
    <form action="{{ route('tps.import.simpan') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body">
                            <div class="form-group mb-5">
                                <label for="file" class="text-dark text-sm font-weight-medium">Pilih File</label>
                                <input type="file" class="form-control" name="file" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Import</button>
                            <a href="{{ route('tps') }}" class="btn btn-outline-primary ml-5">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
