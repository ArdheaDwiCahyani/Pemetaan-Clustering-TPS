@extends('layouts.app')

@section('title', 'Data Kecamatan')
@section('subtitle', 'Form Tambah Data')

@section('content')
    <form action="{{ route('kecamatan.tambah.simpan') }}" method="post" id="myForm">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <label for="namaKecamatan" class="text-dark text-sm font-weight-medium">Nama</label>
                                <input type="text" class="form-control" id="namaKecamatan" name="namaKecamatan"
                                    placeholder="Masukkan Nama Kecamatan">
                                {{-- Elemen alert --}}
                                <small id="errorNamaKecamatan" class="text-danger" style="display: none;">
                                    Kolom tidak boleh kosong!
                                </small>
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

    {{-- Membuat Alert Form --}}
    <script>
        document.getElementById("myForm").addEventListener("submit", function(event) {
            // Ambil elemen input namaKecamatan dan elemen pesan kesalahan
            var namaKecamatan = document.getElementById("namaKecamatan");
            var errorNamaKecamatan = document.getElementById("errorNamaKecamatan");

            // Cek jika kolom namaKecamatan masih kosong
            if (namaKecamatan.value.trim() === "") {
                event.preventDefault(); // Menghentikan form dari pengiriman
                errorNamaKecamatan.style.display = "block"; // Menampilkan pesan kesalahan
                namaKecamatan.focus(); // Fokus ke kolom namaKecamatan
            } else if (/[^a-zA-Z\s]/.test(namaKecamatan.value.trim())) {
                event.preventDefault(); // Menghentikan form dari pengiriman
                errorNamaKecamatan.textContent = "Kolom hanya boleh berisi huruf!";
                errorNamaKecamatan.style.display = "block"; // Menampilkan pesan kesalahan
                namaKecamatan.focus(); // Fokus ke kolom namaKecamatan
            } else if (namaKecamatan.value.trim().length < 5) {
                event.preventDefault();
                errorNamaKecamatan.textContent = "Nama kecamatan minimal 5 karakter";
                errorNamaKecamatan.style.display = "block";
                namaKecamatan.focus();
            } else if (namaKecamatan.value.trim().length > 100) {
                event.preventDefault();
                errorNamaKecamatan.textContent = "Nama kecamatan maksimal 100 karakter";
                errorNamaKecamatan.style.display = "block";
                namaKecamatan.focus();
            }
        });

        // Menyembunyikan pesan kesalahan saat pengguna mulai mengetik
        document.getElementById("namaKecamatan").addEventListener("input", function() {
            var errorNamaKecamatan = document.getElementById("errorNamaKecamatan");
            if (errorNamaKecamatan.style.display === "block") {
                errorNamaKecamatan.style.display = "none"; // Sembunyikan pesan kesalahan
            }
        });
    </script>
@endsection
