<?php

namespace App\Http\Controllers;

use App\Exports\KelurahanExport;
use App\Imports\KelurahanImport;
use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class kelurahanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data kecamatan untuk dropdown filter
        $kecamatan = Kecamatan::all();

        // Ambil nilai filter dan jumlah item per halaman dari query string
        $selectedKecamatan = $request->input('kecamatan_id');
        $perPage = $request->input('per_page', 4);

        // Query data kelurahan dengan filtering dan pagination
        $kelurahan = Kelurahan::with('kecamatan')
            ->when($selectedKecamatan, function ($query) use ($selectedKecamatan) {
                $query->where('kecamatan_id', $selectedKecamatan);
            })
            ->paginate($perPage); // Gunakan jumlah item per halaman dari input

        return view('kelurahan.index', compact('kelurahan', 'kecamatan', 'selectedKecamatan', 'perPage'));
    }

    public function allKelurahan()
    {
        // Ambil semua data Kelurahan
        $allKelurahan = Kelurahan::all();

        // Ubah data untuk mengganti kecamatan_id dengan nama kecamatan
        $response = $allKelurahan->map(function ($kelurahan) {
            $kecamatan = Kecamatan::find($kelurahan->kecamatan_id);
            return [
                'id' => $kelurahan->id,
                'namaKelurahan' => $kelurahan->namaKelurahan,
                'kecamatan' => $kecamatan ? $kecamatan->namaKecamatan : 'Tidak Diketahui', // Handle jika kecamatan tidak ditemukan
            ];
        });

        // Return dalam format JSON
        return response()->json($response);
    }

    public function handleSearch(Request $request)
    {
        // Ambil nilai input dari form
        $search = $request->input('searchInput');

        $columns = Schema::getColumnListing('kelurahan');

        // Mulai query pencarian
        $query = Kelurahan::query();

        // Tambahkan kondisi pencarian untuk setiap kolom
        foreach ($columns as $column) {
            $query->orWhere($column, 'LIKE', '%' . $search . '%');
        }

        // Eksekusi query dan dapatkan hasil
        $results = $query->get();

        // Kembalikan data ke view
        return response()->json(compact('results', 'search'));
    }


    public function tambah()
    {
        $kecamatan = Kecamatan::where('namaKecamatan', '!=', '-- Pilih Kecamatan --')->get();
        return view('kelurahan.form', compact('kecamatan'));
    }

    public function simpan(Request $request)
    {
        $validatedData = $request->validate([
            'namaKelurahan' => 'required|string|min:5|max:100',
            'kecamatan_id' => 'required'
        ]);

        Kelurahan::create([
            'namaKelurahan' => $validatedData['namaKelurahan'],
            'kecamatan_id' => $validatedData['kecamatan_id']
        ]);

        return redirect()->route('kelurahan');
    }

    public function edit($id)
    {
        $kelurahan = Kelurahan::find($id);
        $kecamatan = Kecamatan::all();

        return view('kelurahan.formEdit', compact('kelurahan', 'kecamatan'));
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'namaKelurahan' => 'required|string|min:5|max:100',
            'kecamatan_id' => 'required'
        ]);

        $kelurahan = Kelurahan::find($id);
        $kelurahan->update([
            'namaKelurahan' => $validatedData['namaKelurahan'],
            'kecamatan_id' => $validatedData['kecamatan_id'],
        ]);

        return redirect()->route('kelurahan');
    }

    public function hapus($id)
    {
        $kelurahan = Kelurahan::find($id);

        if ($kelurahan) {
            $kelurahan->delete(); // Menghapus kelurahan
            return response()->json(['message' => 'Item deleted successfully.']);
        } else {
            return response()->json(['message' => 'Item not found.'], 404);
        }
    }

    //fungsi import
    public function importForm()
    {
        return view('kelurahan.import');
    }

    //proses file import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        //ambil file yg diupload
        $file = $request->file('file');
        Excel::import(new KelurahanImport, $file);

        return redirect()->route('kelurahan');
    }

    public function export()
    {
        return Excel::download(new KelurahanExport, 'data-kelurahan.xlsx');
    }
}
