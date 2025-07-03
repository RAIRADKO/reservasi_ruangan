<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan form login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani permintaan login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // 1. Validasi form input untuk NIP (bisa juga username) dan password
        $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $remember = $request->filled('remember');

        // Kredensial untuk user biasa (berdasarkan NIP)
        $credentialsUser = [
            'nip' => $request->nip,
            'password' => $request->password,
        ];

        // Kredensial untuk admin (berdasarkan username dari kolom NIP)
        $credentialsAdmin = [
            'username' => $request->nip,
            'password' => $request->password,
        ];

        // 2. Coba autentikasi sebagai user biasa
        if (Auth::guard('web')->attempt($credentialsUser, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // 3. Jika gagal, coba autentikasi sebagai admin
        if (Auth::guard('admin')->attempt($credentialsAdmin, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // 4. Jika keduanya gagal, kembalikan dengan pesan error
        return back()->withErrors([
            'nip' => 'NIP/Username atau password yang Anda masukkan salah.',
        ])->withInput($request->only('nip')); // Hanya kembalikan input 'nip'
    }

    /**
     * Melakukan logout pengguna atau admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Logout dari guard manapun yang sedang aktif
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}