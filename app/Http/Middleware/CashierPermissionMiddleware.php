<?php

namespace App\Http\Middleware;

use Closure;
use App\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift;

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
                $shift = Shift::whereNotNull('start_date_time')
                                    ->whereNull('end_date_time')
                                    ->first();
                if ($shift == null) {
                    return redirect('/shift-close');
                } else {
                    if ($request->has('shift_id')) {
                        if ($request->get('shift_id') != $shift->id) {
                            return redirect('/logout');
                        } else {
                            return $next($request);
                        }
                    } else {
                        return $next($request);
                    }
                }
            } else {
                return redirect('/unauthorze');
            }

        } else {
            return redirect('/login');
        }
    }
}
