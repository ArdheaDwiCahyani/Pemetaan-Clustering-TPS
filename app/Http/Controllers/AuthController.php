<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\PasswordResetMail;

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
            return redirect()->route('dashboard'); 
        }

        return back()->withErrors(['email' => 'Wrong email', 'password' => 'Wrong password']);
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('dashboard'); // Ganti 'dashboard' dengan nama route Anda
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

    public function sendResetLink(Request $request)
    {
        // Validasi email
        $request->validate([
            'email' => 'required|email', // Validasi email yang ada di database
        ]);
        // dd($request->only('email'));

        // Kirimkan link reset password
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed', // Validasi password
            'token' => 'required', // Token harus ada
        ]);

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // Cek jika password berhasil direset
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password Anda telah berhasil direset.');
        }

        return back()->withErrors(['email' => 'Token reset password tidak valid.']);
    }

    function index()
    {
        Mail::to('dhea@gmail.com')->send(new PasswordResetMail());
    }
}
