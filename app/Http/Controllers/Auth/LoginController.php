<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required'],
        ]);
        $remember = $request->filled('remember');
        $credentialsUser = [
            'nip' => $request->nip,
            'password' => $request->password,
        ];
        if (Auth::guard('web')->attempt($credentialsUser, $remember)) {
            $user = Auth::guard('web')->user();
            if ($user->status !== 'approved') {
                $status = $user->status;
                Auth::guard('web')->logout();
                $errorMessage = 'Akun Anda belum disetujui oleh admin.';
                if ($status === 'rejected') {
                    $errorMessage = 'Pendaftaran akun Anda ditolak oleh admin.';
                }
                 return back()->withErrors([
                    'nip' => $errorMessage,
                ])->withInput($request->only('nip', 'remember'));
            }
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        $credentialsAdmin = [
            'username' => $request->nip,
            'password' => $request->password,
        ];
        if (Auth::guard('admin')->attempt($credentialsAdmin, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }
        return back()->withErrors([
            'nip' => 'NIP/Username atau password yang Anda masukkan salah.',
        ])->withInput($request->only('nip'));
    }
    public function logout(Request $request)
    {
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