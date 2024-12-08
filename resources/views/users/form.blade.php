@extends('layouts.app')

@section('title', 'Data User')

@section('content')
    <form action="{{ route('user.tambah.simpan') }}" method="post" id="myForm">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body pb-1">
                            <div class="form-group mb-4">
                                <label for="name" class="text-dark text-sm font-weight-medium">Username</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Masukkan Username">
                                {{-- Elemen alert --}}
                                <small id="errorName" class="text-danger" style="display: none;">
                                    Kolom tidak boleh kosong!
                                </small>
                            </div>
                            <div class="form-group mb-4">
                                <label for="email" class="text-dark text-sm font-weight-medium">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Masukkan Email">
                                {{-- Elemen alert --}}
                                <small id="errorEmail" class="text-danger" style="display: none;">
                                    Kolom tidak boleh kosong!
                                </small>
                            </div>
                            <div class="form-group mb-4">
                                <label for="password" class="text-dark text-sm font-weight-medium">Password</label>
                                <input type="text" class="form-control" id="password" name="password"
                                    placeholder="Masukkan Password">
                                
                            </div>
                            <div class="form-group mb-0">
                                <label for="role" class="text-dark text-sm font-weight-medium">Role</label>
                                <input type="text" class="form-control" id="role" name="role" value="admin"
                                    readonly>
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
            var name = document.getElementById("name");
            var errorName = document.getElementById("errorName");
            var email = document.getElementById("email");
            var errorEmail = document.getElementById("errorEmail");

            var isFormValid = true;

            // Cek jika kolom nama masih kosong
            if (name.value.trim() === "") {
                event.preventDefault(); // Menghentikan form dari pengiriman
                errorName.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else if (/[^a-zA-Z\s\-']/.test(nama.value.trim())) {
                event.preventDefault(); // Menghentikan form dari pengiriman
                errorName.textContent = "Kolom hanya boleh berisi huruf!";
                errorName.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else if (nama.value.trim().length < 3) {
                event.preventDefault();
                errorName.textContent = "Nama minimal 3 karakter";
                errorName.style.display = "block";
                isFormValid = false;
            } else if (nama.value.trim().length > 50) {
                event.preventDefault();
                errorName.textContent = "Nama maksimal 50 karakter";
                errorName.style.display = "block";
                isFormValid = false;
            } else {
                errorName.style.display = "none"; // Sembunyikan pesan kesalahan jika tidak ada error
            }

            if (email.value.trim() === "") {
                event.preventDefault(); // Menghentikan form dari pengiriman
                errorEmail.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                event.preventDefault(); // Menghentikan form dari pengiriman
                errorEmail.textContent = "Format email tidak valid!";
                errorEmail.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else if (email.value.trim().length < 3) {
                event.preventDefault(); // Menghentikan form dari pengiriman
                errorEmail.textContent = "Email minimal 3 karakter!";
                errorEmail.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else if (email.value.trim().length > 50) {
                event.preventDefault(); // Menghentikan form dari pengiriman
                errorEmail.textContent = "Email maksimal 50 karakter!";
                errorEmail.style.display = "block"; // Menampilkan pesan kesalahan
                isFormValid = false;
            } else {
                errorEmail.style.display = "none"; // Sembunyikan pesan kesalahan jika tidak ada error
            }

            // Cegah pengiriman form jika tidak valid
            if (!isFormValid) {
                event.preventDefault();
            }

        });

        // Menyembunyikan pesan kesalahan saat pengguna mulai mengetik
        document.getElementById("name").addEventListener("input", function() {
            var errorName = document.getElementById("errorName");
            if (errorName.style.display === "block") {
                errorName.style.display = "none"; // Sembunyikan pesan kesalahan
            }
        });

        document.getElementById("email").addEventListener("input", function() {
            var errorEmail = document.getElementById("errorEmail");
            if (errorEmail.style.display === "block") {
                errorEmail.style.display = "none"; // Sembunyikan pesan kesalahan
            }
        });
    </script>
@endsection
