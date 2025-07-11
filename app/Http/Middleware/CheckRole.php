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

        // Superadmin selalu punya akses
        if ($admin->role === 'superadmin') {
            return $next($request);
        }

        // Cek jika role admin sesuai
        if (in_array($admin->role, $roles)) {
            $reservation = $request->route('reservation');
            $room = $request->route('room');

            // Cek kepemilikan reservasi
            if ($reservation && $reservation->roomInfo->instansi_id !== $admin->instansi_id) {
                return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke reservasi ini.');
            }

            // Cek kepemilikan ruangan
            if ($room && $room->instansi_id !== $admin->instansi_id) {
                return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke ruangan ini.');
            }
            
            return $next($request);
        }

        return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}