<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use Illuminate\Http\Request;

class parameterController extends Controller
{
    public function index()
    {
        $parameter = Parameter::get();
        return view('parameter.index', compact('parameter'));
    }

    public function allParams()
    {
        $params = Parameter::all();
        
        return response()->json($params);
    }

    public function tambah()
    {
        $parameter = Parameter::all();
        return view('parameter.form', compact('parameter'));
    }

    public function simpan(Request $request)
    {
        $validatedData = $request->validate([
            'namaParameter' => 'required|string|min:5|max:50',
        ]);

        Parameter::create([
            'namaParameter' => $validatedData['namaParameter'],
        ]);

        return redirect()->route('parameter');
    }

    public function edit($id)
    {
        $parameter = Parameter::find($id);

        return view('parameter.formEdit', compact('parameter'));
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'namaParameter' => 'required|string|min:5|max:50',
        ]);

        $parameter = Parameter::find($id);

        $parameter->update([
            'namaParameter' => $validatedData['namaParameter'],
        ]);
        
        return redirect()->route('parameter');
    }

    public function hapus($id)
    {
        $parameter = Parameter::find($id);
        $parameter->delete(); // Menghapus parameter
    }
}
