<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        foreach ($roles as $role) {
            // Jika pengguna memiliki peran yang diizinkan, lanjutkan.
            if ($admin->role == $role) {
                return $next($request);
            }
        }

        // Jika tidak, kembalikan ke dashboard dengan pesan error.
        return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}