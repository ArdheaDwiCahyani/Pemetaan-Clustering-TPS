<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard'); // Atau sesuaikan dengan route yang Anda inginkan
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }


    public function showRegisterForm()
    {
        return view('auth.register');
    }

    //proses
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:dns|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Account  created succesfully. Please log in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); // Menghapus session
        $request->session()->regenerateToken(); // Regenerasi token CSRF
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-pw');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->save();

        return redirect()->route('login');
    }
}
