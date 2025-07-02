<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan file view-nya bernama login.blade.php di resources/views/auth
    }

    public function login(Request $request)
    {
        // Validasi form input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login dengan Auth::attempt()
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Wajib: mencegah session fixation
            return redirect()->intended('/');  // Redirect ke halaman utama atau terakhir dikunjungi
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Hapus login user

        $request->session()->invalidate();     // Kosongkan session
        $request->session()->regenerateToken(); // Regenerasi CSRF token

        return redirect('/');
    }
}
