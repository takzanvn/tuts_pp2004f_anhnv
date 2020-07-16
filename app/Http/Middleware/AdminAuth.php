<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('admin*') || $request->is('admin/*')) {
            if (!Auth::guard('admin')->check() && !$request->is('admin/login')) {
                return redirect('admin/login');
            }
        }

        return $next($request);
    }
}
