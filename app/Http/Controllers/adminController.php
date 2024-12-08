<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class adminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();

        return view('users.index', compact('admins'));
    }

    public function allUser()
    {
        $admins = User::where('role', 'admin')->get();

        $adminData = $admins->map(function ($admin, $index) {
            return [
                'no' => $index + 1,
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
            ];
        });

        return response()->json($adminData);
    }

    public function tambah()
    {
        return view('users.form');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('user')->with('success', 'Admin berhasil dibuat.');
    }

    public function edit($id)
    {
        $admins = User::findOrFail($id);
        return view('users.formEdit', compact('admins'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email',
            'password' => 'nullable|min:8',
        ]);

        $admins = User::findOrFail($id);
        $admins->name = $request->name;
        $admins->email = $request->email;

        if ($request->filled('password')) {
            $admins->password = Hash::make($request->password);
        }

        $admins->save();

        return redirect()->route('user')->with('success', 'Admin berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $admins = User::findOrFail($id);
        $admins->delete();

        return redirect()->route('user')->with('success', 'Admin berhasil dihapus.');
    }

}
