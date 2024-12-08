@extends('layouts.app')

@section('title', 'Form Edit Data')

@section('content')
    <form action="{{ route('tps.tambah.update', $tps->id) }}" method="post" id="myForm">
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
                                <small id="errorNamaTPS" class="text-danger" style="display: none; margin-bottom: 20px">
                                    Kolom tidak boleh kosong!
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="kelurahans_id" class="text-dark text-sm font-weight-medium">Kelurahan</label>
                                <select class="form-control choices-single" name="kelurahans_id" id="kelurahans_id">
                                    <option value="" disabled selected>--- Pilih kelurahan ---</option>
                                    @foreach ($kelurahan as $row)
                                        <option value="{{ $row->id }}"
                                            {{ $row->id == $tps->kelurahans_id ? 'selected' : '' }}>
                                            {{ $row->namaKelurahan }}
                                        </option>
                                    @endforeach
                                    <small id="errorKelurahan" class="text-danger"
                                        style="display: none; margin-top: -20px;">
                                        Kolom tidak boleh kosong!
                                    </small>
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                @foreach ($parameter as $param)
                                    @if ($param->namaParameter == 'Jarak ke TPA')
                                        <div class="form-group mb-4">
                                            <label for="param{{ $param->id }}"
                                                class="text-dark text-sm font-weight-medium">{{ $param->namaParameter }}
                                                (Km)
                                            </label>
                                            <input type="hidden" name="params_id[]" value="{{ $param->id }}">
                                            {{-- Mengambil nilai_parameter dari hubungan pivot berdasarkan params_id --}}
                                            <input type="number" name="nilai_parameter[]" id="param{{ $param->id }}"
                                                class="form-control"
                                                value="{{ $tps->parameter->where('id', $param->id)->first()->pivot->nilai_parameter ?? '' }}"
                                                step="0.01">
                                            <small id="errorNilaiJarak" class="text-danger"
                                                style="display: none; margin-top: -20px; margin-bottom: 20px">
                                                Kolom tidak boleh kosong!
                                            </small>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="form-group mb-4">
                                <label for="longitude" class="text-dark text-sm font-weight-medium">Koordinat Longitude
                                    (X)</label>
                                <input type="number" class="form-control" id="longitude" name="longitude" step="any"
                                    placeholder="Masukkan Koordinat Longitude"
                                    value="{{ isset($tps) ? $tps->longitude : '' }}">
                                <small id="errorLongitude" class="text-danger" style="display: none;">
                                    Kolom tidak boleh kosong!
                                </small>
                            </div>
                            <div class="form-group mb-4">
                                <label for="latitude" class="text-dark text-sm font-weight-medium">Koordinat Latitude
                                    (Y)</label>
                                <input type="number" class="form-control" id="latitude" name="latitude" step="any"
                                    placeholder="Masukkan Koordinat Latitude"
                                    value="{{ isset($tps) ? $tps->latitude : '' }}">
                                <small id="errorLatitude" class="text-danger" style="display: none;">
                                    Kolom tidak boleh kosong!
                                </small>
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
        document.getElementById("myForm").addEventListener("submit", function(event) {
            var namaTPS = document.getElementById("namaTPS");
            var errorNamaTPS = document.getElementById("errorNamaTPS");
            var kelurahanSelect = document.getElementById("kelurahans_id");
            var errorKelurahan = document.getElementById("errorKelurahan");
            var nilaiJarak = document.querySelectorAll('[name="nilai_parameter[]"]');
            var errorNilaiJarak = document.getElementById("errorNilaiJarak");
            var longitude = document.getElementById("longitude");
            var errorLongitude = document.getElementById("errorLongitude");
            var Latitude = document.getElementById("Latitude");
            var errorLatitude = document.getElementById("errorLatitude");

            var isFormValid = true;
            //namatps
            if (namaTPS.value.trim() === "") {
                errorNamaTPS.textContent = "Kolom tidak boleh kosong!";
                errorNamaTPS.style.display = "block";
                isFormValid = false;
            } else if (/[^a-zA-Z\s]/.test(namaTPS.value.trim())) {
                errorNamaTPS.textContent = "Kolom hanya boleh berisi huruf!";
                errorNamaTPS.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else if (namaTPS.value.trim().length < 5) {
                event.preventDefault();
                errorNamaTPS.textContent = "Nama kelurahan minimal 5 karakter";
                errorNamaTPS.style.display = "block";
                isFormValid = false;
            } else if (namaTPS.value.trim().length > 100) {
                event.preventDefault();
                errorNamaTPS.textContent = "Nama kelurahan maksimal 100 karakter";
                errorNamaTPS.style.display = "block";
                isFormValid = false;
            } else {
                errorNamaTPS.style.display = "none"; // Sembunyikan pesan kesalahan jika tidak ada error
            }

            //kelurahan
            if (kelurahanSelect.value === "") {
                errorKelurahan.textContent = "Pilih kelurahan!";
                errorKelurahan.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else {
                errorKelurahan.style.display = "none"; // Sembunyikan pesan kesalahan jika tidak ada error
            }

            //nilai parameter
            nilaiJarak.forEach(function(input) {
                if (input.value.trim() === "") {
                    errorNilaiJarak.textContent = "Kolom tidak boleh kosong!";
                    errorNilaiJarak.style.display = "block";
                    isFormValid = false;
                } else {
                    errorNilaiJarak.style.display = "none";
                }
            });

            //longitude
            if (longitude.value.trim() === "") {
                errorLongitude.textContent = "Kolom tidak boleh kosong!";
                errorLongitude.style.display = "block";
                isFormValid = false;
            } else if (longitude.value.trim() < -180 || longitude.value.trim() >
                180) {
                errorLongitude.textContent = "Longitude harus berupa angka antara -180 hingga 180!";
                errorLongitude.style.display = "block";
                isFormValid = false;
            } else {
                errorLongitude.style.display = "none"; // Sembunyikan pesan kesalahan jika valid
            }

            //latitude
            if (latitude.value.trim() === "") {
                errorLatitude.textContent = "Kolom tidak boleh kosong!";
                errorLatitude.style.display = "block";
                isFormValid = false;
            } else if (latitude.value.trim() < -90 || latitude.value.trim() >
                90) {
                errorLatitude.textContent = "Latitude harus berupa angka antara -180 hingga 180!";
                errorLatitude.style.display = "block";
                isFormValid = false;
            } else {
                errorLatitude.style.display = "none"; // Sembunyikan pesan kesalahan jika valid
            }

            if (!isFormValid) {
                event.preventDefault();
            }
        });

        document.getElementById("namaTPS").addEventListener("input", function() {
            document.getElementById("errorNamaTPS").style.display = "none";
        });

        document.getElementById("kelurahans_id").addEventListener("change", function() {
            document.getElementById("errorKelurahan").style.display = "none";
        });

        var nilaiJarak = document.querySelectorAll("input[name='nilai_parameter[]']");
        nilaiJarak.forEach(function(input) {
            input.addEventListener("input", function() {
                // Dapatkan elemen error yang sesuai
                var errorElement = document.getElementById("errorNilaiJarak");

                // Sembunyikan pesan kesalahan saat pengguna mengetik
                errorElement.style.display = "none";
            });
        });

        document.getElementById("longitude").addEventListener("input", function() {
            document.getElementById("errorLongitude").style.display = "none";
        });

        document.getElementById("latitude").addEventListener("input", function() {
            document.getElementById("errorLatitude").style.display = "none";
        });
    </script>
@endsection
