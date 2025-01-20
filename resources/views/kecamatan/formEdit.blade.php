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

    <script>
        document.getElementById("myForm").addEventListener("submit", function(event) {
            // Ambil elemen input nama kecamatan
            var namaKecamatan = document.getElementById("namaKecamatan");
            var errorNamaKecamatan = document.getElementById("errorNamaKecamatan");

            // Variabel untuk melacak apakah form valid atau tidak
            var isFormValid = true;

            // Cek jika kolom namaKecamatan masih kosong
            if (namaKecamatan.value.trim() === "") {
                errorNamaKecamatan.textContent = "Kolom tidak boleh kosong!";
                errorNamaKecamatan.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else if (/[^a-zA-Z\s]/.test(namaKecamatan.value.trim())) {
                errorNamaKecamatan.textContent = "Kolom hanya boleh berisi huruf!";
                errorNamaKecamatan.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else if (namaKecamatan.value.trim().length < 5) {
                event.preventDefault();
                errorNamaKecamatan.textContent = "Nama kecamatan minimal 5 karakter";
                errorNamaKecamatan.style.display = "block";
                isFormValid = false;
            } else if (namaKecamatan.value.trim().length > 100) {
                event.preventDefault();
                errorNamaKecamatan.textContent = "Nama kecamatan maksimal 100 karakter";
                errorNamaKecamatan.style.display = "block";
                isFormValid = false;
            } else {
                errorNamaKecamatan.style.display = "none"; // Sembunyikan pesan kesalahan jika tidak ada error
            }

            // Cegah pengiriman form jika tidak valid
            if (!isFormValid) {
                event.preventDefault();
            }
        });

        // Menyembunyikan pesan kesalahan saat pengguna mulai mengetik pada namaKecamatan
        document.getElementById("namaKecamatan").addEventListener("input", function() {
            document.getElementById("errorNamaKecamatan").style.display = "none";
        });
    </script>
@endsection
