<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Terapkan middleware 'guest' untuk guard 'admin', kecuali untuk method 'logout'
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Menampilkan form login untuk admin.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Menggunakan layout admin untuk konsistensi
        return view('auth.admin-login');
    }

    /**
     * Menangani permintaan login dari admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba untuk melakukan autentikasi dengan guard 'admin'
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Username atau password yang diberikan salah.',
        ])->withInput($request->only('username', 'remember'));
    }

    /**
     * Melakukan logout untuk admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman utama setelah logout
        return redirect('/');
    }
}