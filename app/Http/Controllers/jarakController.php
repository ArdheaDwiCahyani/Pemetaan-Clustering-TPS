<?php

namespace App\Http\Controllers;

use App\Imports\JarakImport;
use App\Models\Jarak;
use App\Models\Tps;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class jarakController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 4);
        $tpsAsalId = $request->input('tps_asal');
        $jarakQuery = Jarak::with(['tpsAsal', 'tpsTujuan']);

        if ($tpsAsalId) {
            $jarakQuery->where('tps_asal_id', $tpsAsalId);
        }

        $jarak = $jarakQuery->paginate($perPage);
        $tpsList = Tps::all();

        return view('jarak.index', compact('jarak', 'tpsList', 'tpsAsalId'));
    }


    public function tambah()
    {
        $tps = Tps::all();
        return view('jarak.form', compact('tps'));
    }

    public function simpan(Request $request)
    {
        $validatedData = $request->validate([
            'tps_asal_id' => 'required|exists:tps,id',
            'tps_tujuan_id' => 'required|exists:tps,id|different:tps_asal_id',
            'jarak' => 'required|numeric|min:0',
        ]);

        Jarak::create([
            'tps_asal_id' => $validatedData['tps_asal_id'],
            'tps_tujuan_id' => $validatedData['tps_tujuan_id'],
            'jarak' => $validatedData['jarak'],
        ]);

        return redirect()->route('jarak');
    }

    public function edit($id)
    {
        $jarak = Jarak::find($id);
        $tps = Tps::all();

        return view('jarak.formEdit', compact('jarak', 'tps'));
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'tps_asal_id' => 'required|exists:tps,id',
            'tps_tujuan_id' => 'required|exists:tps,id|different:tps_asal_id',
            'jarak' => 'required|numeric|min:0',
        ]);

        $jarak = Jarak::find($id);

        $jarak->update([
            'tps_asal_id' => $validatedData['tps_asal_id'],
            'tps_tujuan_id' => $validatedData['tps_tujuan_id'],
            'jarak' => $validatedData['jarak'],
        ]);

        return redirect()->route('jarak');
    }

    public function hapus($id)
    {
        $jarak = Jarak::find($id);

        if ($jarak) {
            $jarak->delete(); // Menghapus jarak
            return response()->json(['message' => 'Item deleted successfully.']);
        } else {
            return response()->json(['message' => 'Item not found.'], 404);
        }
    }

    //fungsi import
    public function importForm()
    {
        return view('jarak.import');
    }

    //proses file import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        //ambil file yg diupload
        $file = $request->file('file');

        Excel::import(new JarakImport, $file);

        return redirect()->route('jarak');
    }
}
