<?php

namespace App\Http\Middleware;

use App\Constant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->user() != null) {
            $role = Auth::guard('admin')->user()->role;
            if ($role == Constant::ADMIN_ROLE) {
                return $next($request);
            } else {
                return redirect('/sg-backend/unauthorize');
            }
        } else {
            return redirect('/sg-backend/login');
        }
    }
}
