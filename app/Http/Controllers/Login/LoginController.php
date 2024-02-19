<?php

namespace App\Http\Controllers\Login;

use App\Constant;
use App\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminAuthRequest;
use App\Http\Requests\CashierAuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct()
    {
        DB::connection()->enableQueryLog();
    }

    public function getLoginForm()
    {
        try {

            if (Auth::guard('admin')->user() != null) {
                $role = Auth::guard('admin')->user()->role;
                if ($role == Constant::ADMIN_ROLE) {
                    return redirect('/sg-backend/index');
                }
            }
            return view('Login.backend_login');
        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function postLoginForm(AdminAuthRequest $request)
    {
        try {
            $auth = Auth::guard('admin')->attempt([
                'username' => $request->username,
                'password' => $request->password,
            ]);
            $screen = "Post Login Form";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);

            if ($auth) {
                return redirect('/sg-backend/index');
            } else {
                return redirect()->back()->withErrors(['login_error' => 'Wrong Credential!'])->withInput();
            }
        } catch (\Exception $e) {

            $screen = "Post Login Form";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            Session::flush();
            return redirect('/sg-backend/login');
        } catch (\Exception $e) {
            abort(500);
        }
    }
    public function frontendLogout()
    {
        try {
            Auth::logout();
            Session::flush();
            return redirect('/login');
        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function unauthorize()
    {
        try {
            return view('auth.access_denied');
        } catch (\Exception $e) {
            abort(500);
        }
    }


    public function getFrontendLoginForm()
    {
        try {

            // if (Auth::guard('admin')->user() != null) {
            //     $role = Auth::guard('admin')->user()->role;
            //     if ($role == Constant::CASHIER_ROLE) {
            //         return redirect('/order');
            //     }
            // }
            return view('Login.frontend_login');
        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function postFrontendlogin(CashierAuthRequest $request)
    {
        try {
            $auth = Auth::guard('admin')->attempt([
                'username' => $request->username,
                'password' => $request->password,
            ]);
            $screen = "FrontendLogin from LoginController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            if ($auth) {
                $shift_id = Utility::getShift();
                if ($shift_id != null) {
                    Session::put('shift_id', $shift_id->id);
                }
                return redirect('/order');
            } else {
                return redirect()->back()->withErrors(['login_error' => 'Wrong Credential!'])->withInput();
            }
        } catch (\Exception $e) {

            $screen = "Post Login Form";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
