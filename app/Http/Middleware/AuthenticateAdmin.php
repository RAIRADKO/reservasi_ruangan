<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah admin sudah login menggunakan guard 'admin'
        if (!Auth::guard('admin')->check()) {
            // Arahkan ke halaman login utama jika tidak terautentikasi
            return redirect()->route('login');
        }

        return $next($request);
    }
}