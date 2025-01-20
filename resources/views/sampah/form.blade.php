@extends('layouts.app')

@section('content')
    <form action="{{ route('sampah.tambah.simpan') }}" method="post" id="myForm">
        @csrf
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body2 mb-0">
                            <!-- Pesan Error untuk Kombinasi tps_id dan Tahun -->
                            @if ($errors->has('tps_tahun_exists'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('tps_tahun_exists') }}
                                </div>
                            @endif

                            <!-- Pilih TPS -->
                            <div class="form-group mb-4">
                                <label for="tps_id" class="text-dark text-sm font-weight-medium">Nama TPS</label>
                                <select name="tps_id" id="tps_id" class="form-control choices-single">
                                    <option value="" disabled selected>-- Pilih TPS --</option>
                                    @foreach ($tps as $row)
                                        <option value="{{ $row->id }}">{{ $row->namaTPS }}</option>
                                    @endforeach
                                </select>
                                <small id="errorNamaTPS" class="text-danger" style="display: none; margin-top: -20px;">
                                    Kolom tidak boleh kosong!
                                </small>
                            </div>

                            <!-- Tahun -->
                            <div class="form-group mb-4">
                                <label for="tahun" class="text-dark text-sm font-weight-medium">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" readonly>
                                <small id="errorTahun" class="text-danger" style="display: none;">
                                    Kolom tidak boleh kosong!
                                </small>
                            </div>

                            <!-- Parameter Volume Sampah -->
                            <div class="form-group mb-0">
                                <div class="form-group mb-4">
                                    <label for="volumeSampah"
                                        class="text-dark text-sm font-weight-medium">Volume Sampah (Ton)
                                    </label>
                                    <input type="number" name="volumeSampah" id="volumeSampah"
                                        class="form-control" placeholder="Masukkan Volume Sampah" step="any">
                                    <small id="errorVolumeSampah" class="text-danger" style="display: none;">
                                        Kolom tidak boleh kosong atau kurang dari 0!
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer mt-0">
                            <button type="submit" class="btn btn-primary bs-btn-active-bg">Simpan</button>
                            <a href="{{ route('sampah') }}" class="btn btn-outline-primary ms-1">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- script untuk mengatur nilai default pada kolom input tahun sesuai dengan tahun terpilih --}}
    <script>
        // Ambil nilai dari localStorage dengan key 'selectedYear'
        const selectedYear = localStorage.getItem('selectedYear');

        // Cek apakah nilai ada di localStorage
        if (selectedYear) {
            // Set nilai input tahun
            document.getElementById('tahun').value = selectedYear;
        } else {
            // Tampilkan error jika localStorage kosong
            document.getElementById('errorTahun').style.display = 'block';
        }
    </script>

    {{-- script untuk validasi inputan --}}
    <script>
        document.getElementById("myForm").addEventListener("submit", function(event) {
            let isFormValid = true;

            // Validasi TPS
            const tps = document.getElementById("tps_id");
            const errorNamaTPS = document.getElementById("errorNamaTPS");
            if (!tps.value) {
                errorNamaTPS.style.display = "block";
                isFormValid = false;
            } else {
                errorNamaTPS.style.display = "none";
            }

            // Validasi Tahun
            const tahun = document.getElementById("tahun");
            const errorTahun = document.getElementById("errorTahun");
            if (!tahun.value || tahun.value.trim() === "") {
                errorTahun.style.display = "block";
                isFormValid = false;
            } else {
                errorTahun.style.display = "none";
            }

            // Validasi Volume Sampah
            const volumeSampah = document.getElementById("volumeSampah");
            const errorVolumeSampah = document.getElementById("errorVolumeSampah");
            if (volumeSampah.value === "") {
                errorVolumeSampah.textContent = "Kolom tidak boleh kosong!";
                errorVolumeSampah.style.display = "block";
                isFormValid = false;
            } else {
                errorVolumeSampah.style.display = "none";
            }

            // Cegah submit jika tidak valid
            if (!isFormValid) {
                event.preventDefault();
            }
        });

        // Event Listener untuk sembunyikan error saat input berubah
        document.getElementById("tps_id").addEventListener("change", function() {
            document.getElementById("errorNamaTPS").style.display = "none";
        });

        const tahunInput = document.getElementById("tahun");
        tahunInput.addEventListener("input", function() {
            document.getElementById("errorTahun").style.display = "none";
        });

        const volumeInputs = document.querySelectorAll("input[name^='volume_sampah']");
        volumeInputs.forEach((input) => {
            input.addEventListener("input", function() {
                const errorElement = document.getElementById(errorVolume_$ {
                    input.id.split('_')[2]
                });
                errorElement.style.display = "none";
            });
        });
    </script>
@endsection
