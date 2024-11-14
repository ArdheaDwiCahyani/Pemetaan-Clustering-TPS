<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class kecamatanController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::get();
        return view('kecamatan.index', compact('kecamatan'));
    }

    public function tambah()
    {
        $kecamatan = Kecamatan::all();
        return view('kecamatan.form', compact('kecamatan'));
    }

    public function simpan(Request $request)
    {
        //validasi input
        $validatedData = $request->validate([
            'namaKecamatan' => 'required|string|min:5|max:100',
        ]);

        Kecamatan::create([
            'namaKecamatan' => $validatedData['namaKecamatan']
        ]);

        return redirect()->route('kecamatan');
    }

    public function edit($id)
    {
        $kecamatan = Kecamatan::find($id);

        return view('kecamatan.formEdit', [
            'kecamatan' => $kecamatan
        ]);
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'namaKecamatan' => 'required|string|min:5|max:100',
        ]);

        $kecamatan = Kecamatan::find($id);
        $kecamatan->update([
            'namaKecamatan' => $validatedData['namaKecamatan'],
        ]);

        return redirect()->route('kecamatan');
    }

    public function hapus($id)
    {
        $kecamatan = Kecamatan::find($id);

        if ($kecamatan) {
            $kecamatan->delete(); // Menghapus kecamatan
            return response()->json(['message' => 'Item deleted successfully.']);
        } else {
            return response()->json(['message' => 'Item not found.'], 404);
        }
    }
}
