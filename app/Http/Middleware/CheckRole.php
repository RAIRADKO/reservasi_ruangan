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

        if ($admin->role === 'superadmin') {
            return $next($request);
        }

        $reservation = $request->route('reservation');

        if (in_array($admin->role, $roles)) {
            if ($reservation && $reservation->roomInfo->instansi_id !== $admin->instansi_id) {
                return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke reservasi ini.');
            }
            return $next($request);
        }

        return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}