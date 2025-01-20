<?php

namespace App\Http\Controllers;

use App\Exports\JarakExport;
use App\Imports\JarakImport;
use App\Models\Jarak;
use App\Models\Tps;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class jarakController extends Controller
{
    public function index(Request $request)
    {
        $tpsList = Tps::all();

        return view('jarak.index', compact('tpsList'));
    }

    public function allJarak() 
    {
        $jarak = Jarak::all();
        $jarakData = $jarak->map(function ($row, $index) {
            return [
                'no' => $index + 1,
                'id' => $row->id,
                'tps_asal' => $row->tpsAsal->namaTPS,
                'tps_tujuan' => $row->tpsTujuan->namaTPS,
                'jarak' => $row->jarak,
            ];
        });

        return response()->json($jarakData);
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
        $jarak->delete(); // Menghapus jarak
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

        $file = $request->file('file');

        $import = new JarakImport();
        Excel::import($import, $file);

        return redirect()->route('jarak');
    }

    public function export()
    {
        return Excel::download(new JarakExport, 'data-jarak-antar-TPS.xlsx');
    }
}
