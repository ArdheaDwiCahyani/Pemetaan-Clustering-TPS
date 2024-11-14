@extends('layouts.app')

@section('title', 'Form Tambah Data')

@section('content')
    <form action="{{ route('kelurahan.tambah.simpan') }}" method="post" id="myForm">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="namaKelurahan" class="text-dark text-sm font-weight-medium">Kelurahan</label>
                                <input type="text" class="form-control" id="namaKelurahan" name="namaKelurahan"
                                    placeholder="Masukkan Kelurahan">
                                <small id="errorNamaKelurahan" class="text-danger" style="display: none;">
                                    Kolom tidak boleh kosong!
                                </small>
                            </div>
                            <div class="form-group mb-0">
                                <label for="kecamatan_id" class="text-dark text-sm font-weight-medium">Kecamatan</label>
                                <select class="form-control" name="kecamatan_id" id="kecamatan_id">
                                    <option value="0" disabled selected>--- Pilih Kecamatan ---</option>
                                    @foreach ($kecamatan as $row)
                                        <option value="{{ $row->id }}">{{ $row->namaKecamatan }}</option>
                                    @endforeach
                                </select>
                                <small id="errorKecamatan" class="text-danger" style="display: none;">
                                    Harap pilih kecamatan!
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
            // Ambil elemen input namaKelurahan dan elemen pesan kesalahan
            var namaKelurahan = document.getElementById("namaKelurahan");
            var errorNamaKelurahan = document.getElementById("errorNamaKelurahan");
            var kecamatanSelect = document.getElementById("kecamatan_id");
            var errorKecamatan = document.getElementById("errorKecamatan");

            // Variabel untuk melacak apakah form valid atau tidak
            var isFormValid = true;

            // Cek jika kolom namaKelurahan masih kosong
            if (namaKelurahan.value.trim() === "") {
                errorNamaKelurahan.textContent = "Kolom tidak boleh kosong!";
                errorNamaKelurahan.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else if (/[^a-zA-Z\s]/.test(namaKelurahan.value.trim())) {
                errorNamaKelurahan.textContent = "Kolom hanya boleh berisi huruf!";
                errorNamaKelurahan.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else if (namaKelurahan.value.trim().length < 5) {
                event.preventDefault();
                errorNamaKelurahan.textContent = "Nama kelurahan minimal 5 karakter";
                errorNamaKelurahan.style.display = "block";
                isFormValid = false;
            } else if (namaKelurahan.value.trim().length > 100) {
                event.preventDefault();
                errorNamaKelurahan.textContent = "Nama kelurahan maksimal 100 karakter";
                errorNamaKelurahan.style.display = "block";
                isFormValid = false;
            } else {
                errorNamaKelurahan.style.display = "none"; // Sembunyikan pesan kesalahan jika tidak ada error
            }

            // Cek jika opsi kecamatan belum dipilih
            if (kecamatanSelect.value === "0") {
                errorKecamatan.textContent = "Pilih kecamatan!";
                errorKecamatan.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else {
                errorKecamatan.style.display = "none"; // Sembunyikan pesan kesalahan jika tidak ada error
            }

            // Cegah pengiriman form jika tidak valid
            if (!isFormValid) {
                event.preventDefault();
            }
        });

        // Menyembunyikan pesan kesalahan saat pengguna mulai mengetik pada namaKelurahan
        document.getElementById("namaKelurahan").addEventListener("input", function() {
            document.getElementById("errorNamaKelurahan").style.display = "none";
        });

        // Menyembunyikan pesan kesalahan saat pengguna memilih opsi kecamatan
        document.getElementById("kecamatan_id").addEventListener("change", function() {
            document.getElementById("errorKecamatan").style.display = "none";
        });
    </script>

@endsection
