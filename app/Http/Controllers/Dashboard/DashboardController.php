<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Utility;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            return view('backend/home/index');
        } catch (\Exception $e) {
            $screen = "DeleteItem From ItemController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
