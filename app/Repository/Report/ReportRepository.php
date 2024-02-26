<?php

namespace App\Repository\Report;

use App\Constant;
use App\Models\DiscountItem;
use App\Models\Setting;
use App\Utility;
use App\ResponseStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportRepositoryInterface
{
    public function weeklySale(){
        try {
            $data = [];
            $weekly_data = [
                'date' => 'Fri',
                'amount' => 2000
            ];
            $weekly_data1 = [
                'date' => 'Fri',
                'amount' => 2000
            ];
            $weekly_data2 = [
                'date' => 'Fri',
                'amount' => 2000
            ];
             array_push($data,$weekly_data,$weekly_data1,$weekly_data2);
             return $data;
            // $screen = "Shift Open From Shift Index Screen::";
            // $queryLog = DB::getQueryLog();
            // Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "Shift Open From Shift Index Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}

