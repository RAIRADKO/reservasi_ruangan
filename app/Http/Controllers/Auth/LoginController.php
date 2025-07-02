<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani permintaan login untuk User dan Admin.
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        // Atur agar sesi berakhir saat browser ditutup jika "remember me" tidak dicentang
        config(['session.expire_on_close' => !$remember]);

        // Coba login sebagai Admin terlebih dahulu
        if (Auth::guard('admin')->attempt(['username' => $request->identifier, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // Jika gagal, coba login sebagai User (dengan NIP)
        if (Auth::guard('web')->attempt(['nip' => $request->identifier, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        // Jika keduanya gagal, kembalikan error
        throw ValidationException::withMessages([
            'identifier' => [trans('auth.failed')],
        ]);
    }

    /**
     * Menangani permintaan logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}