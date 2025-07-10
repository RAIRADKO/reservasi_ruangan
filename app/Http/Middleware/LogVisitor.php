<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $visitDate = Carbon::today();

        Visitor::firstOrCreate(
            ['ip_address' => $ip, 'visit_date' => $visitDate],
            ['user_agent' => $request->userAgent()]
        );
        
        return $next($request);
    }
}