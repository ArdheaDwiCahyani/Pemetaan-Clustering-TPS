@extends('layouts.app')

@section('title', 'Form Tambah Data')

@section('content')
    <form action="{{ route('parameter.tambah.simpan') }}" method="post" id="myForm">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <label for="namaParameter" class="text-dark text-sm font-weight-medium">Nama Parameter</label>
                                <input type="text" class="form-control" id="namaParameter" name="namaParameter"
                                    placeholder="Masukkan Nama Parameter">
                                <small id="errorNamaParameter" class="text-danger" style="display: none;">
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
            // Ambil elemen input namaParameter dan elemen pesan kesalahan
            var namaParameter = document.getElementById("namaParameter");
            var errorNamaParameter = document.getElementById("errorNamaParameter");

            // Cek jika kolom namaParameter masih kosong
            if (namaParameter.value.trim() === "") {
                event.preventDefault(); // Menghentikan form dari pengiriman
                errorNamaParameter.style.display = "block"; // Menampilkan pesan kesalahan
                namaParameter.focus(); // Fokus ke kolom namaParameter
            } else if (/[^a-zA-Z\s-]/.test(namaParameter.value.trim())) {
                event.preventDefault(); // Menghentikan form dari pengiriman
                errorNamaParameter.textContent = "Kolom hanya boleh berisi huruf!";
                errorNamaParameter.style.display = "block"; // Menampilkan pesan kesalahan
                namaParameter.focus(); // Fokus ke kolom namaParameter
            } else if (namaParameter.value.trim().length < 5) {
                event.preventDefault();
                errorNamaParameter.textContent = "Nama parameter minimal 5 karakter";
                errorNamaParameter.style.display = "block";
                namaParameter.focus();
            } else if (namaParameter.value.trim().length > 100) {
                event.preventDefault();
                errorNamaParameter.textContent = "Nama parameter maksimal 100 karakter";
                errorNamaParameter.style.display = "block";
                namaParameter.focus();
            }
        });

        // Menyembunyikan pesan kesalahan saat pengguna mulai mengetik
        document.getElementById("namaParameter").addEventListener("input", function() {
            var errorNamaParameter = document.getElementById("errorNamaParameter");
            if (errorNamaParameter.style.display === "block") {
                errorNamaParameter.style.display = "none"; // Sembunyikan pesan kesalahan
            }
        });
    </script>
@endsection
