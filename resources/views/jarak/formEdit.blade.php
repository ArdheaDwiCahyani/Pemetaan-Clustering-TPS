@extends('layouts.app')

@section('title', 'Form Edit Jarak')

@section('content')
    <form action="{{ route('jarak.tambah.update', $jarak->id) }} " method="post" id="myForm">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body2 mb-0">
                            <div class="form-group mb-4">
                                <label for="tps_asal_id" class="text-dark text-sm font-weight-medium">TPS Asal</label>
                                <select name="tps_asal_id" id="tps_asal_id" class="form-control choices-single" required>
                                    @foreach ($tps as $row)
                                        <option
                                            value="{{ $row->id }}" {{ $jarak->tps_asal_id == $row->id ? 'selected' : '' }}>
                                            {{ $row->namaTPS }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label for="tps_tujuan_id" class="text-dark text-sm font-weight-medium">TPS Tujuan</label>
                                <select name="tps_tujuan_id" id="tps_tujuan_id" class="form-control choices-single" required>
                                    <option value="" disabled selected>--- Pilih TPS Tujuan ---</option>
                                    @foreach ($tps as $row)
                                        <option
                                            value="{{ $row->id }}" {{ $jarak->tps_tujuan_id == $row->id ? 'selected' : '' }}>
                                            {{ $row->namaTPS }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label for="jarak" class="text-dark text-sm font-weight-medium">Jarak (Km)</label>
                                <input type="number" name="jarak" id="jarak" class="form-control"
                                    placeholder="Masukkan Jarak" step="0.01" min="0"
                                    value="{{ isset($jarak) ? $jarak->jarak : '' }}">
                            </div>
                        </div>
                        <div class="card-footer mt-0">
                            <button type="submit" class="btn btn-primary bs-btn-active-bg">Simpan</button>
                            <a href="{{ route('tps') }}" class="btn btn-outline-primary ms-1">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.getElementById("myForm").addEventListener("submit", function(event){
            var tpsAsal = document.getElementById("tps_asal_id");
            var errorTpsAsal = document.getElementById("errorTpsAsal");
            var tpsTujuan = document.getElementById("tps_tujuan_id");
            var errorTpsTujuan = document.getElementById("errorTpsTujuan");
            var jarak = document.getElementById("jarak");
            var errorJarak = document.getElementById("errorJarak");
            
            var isFormValid = true;

            if (tpsAsal.value === "") {
                errorTpsAsal.textContent = "Pilih TPS Asal!";
                errorTpsAsal.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else {
                errorTpsAsal.style.display = "none"; // Sembunyikan pesan kesalahan jika tidak ada error
            }

            if (tpsTujuan.value === "") {
                errorTpsTujuan.textContent = "Pilih TPS Tujuan!";
                errorTpsTujuan.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else {
                errorTpsTujuan.style.display = "none"; // Sembunyikan pesan kesalahan jika tidak ada error
            }

            if (jarak.value.trim() === "") {
                errorJarak.textContent = "Masukkan Jarak!";
                errorJarak.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else {
                errorJarak.style.display = "none"; // Sembunyikan pesan kesalahan jika tidak ada error
            }

            if (!isFormValid) {
                event.preventDefault();
            }
        });

        document.getElementById("tps_asal_id").addEventListener("change", function() {
            document.getElementById("errorTpsAsal").style.display = "none";
        });

        document.getElementById("tps_tujuan_id").addEventListener("change", function() {
            document.getElementById("errorTpsTujuan").style.display = "none";
        });

        document.getElementById("jarak").addEventListener("input", function() {
            document.getElementById("errorJarak").style.display = "none";
        });
    </script>

@endsection
