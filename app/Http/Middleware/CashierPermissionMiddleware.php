<?php

namespace App\Http\Middleware;

use Closure;
use App\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\ShiftCheckRule;

class CashierPermissionMiddleware
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
            if ($role == Constant::CASHIER_ROLE) {
                $shiftRule = new ShiftCheckRule();
                if ($shiftRule->passes('shift', 'value')) {
                    return redirect('/shift-close');
                }
                return $next($request);
            } else {
                return redirect('/unauthorze');
            }

        } else {
            return redirect('/login');
        }
    }
}
