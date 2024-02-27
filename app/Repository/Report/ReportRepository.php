<?php

namespace App\Repository\Report;

use App\Utility;
use App\Constant;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Shift;
use App\ResponseStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportRepository implements ReportRepositoryInterface
{
    public function weeklySaleGraph()
    {
        try {
            $dates = Utility::getLastSevenDay();
            $result = [];
            foreach ($dates as $shift_date) {
                $shifts = Shift::whereDate('start_date_time', $shift_date)->get();
                if ($shifts != null) {
                    $total_amount = 0;
                    foreach ($shifts as $shift) {
                        $sum_shift = Order::where('shift_id', $shift->id)->sum('total_amount');
                        $total_amount = $total_amount + $sum_shift;
                    }
                    $weekly_date = [
                        'date'   => Carbon::parse($shift_date)->format('D'),
                        'amount' => $total_amount + $sum_shift
                    ];
                    array_push($result, $weekly_date);
                }
            }
            return $result;
            $screen = "SelectWeeklySaleGraph report from ReportRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleGraph report from ReportRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }


    }

    public function monthlySaleGraph()
    {
        try {
            $dates = Utility::getLastSevenDay();
            $result = [];
            foreach ($dates as $shift_date) {
                $shifts = Shift::whereDate('start_date_time', $shift_date)->get();
                if ($shifts != null) {
                    $total_amount = 0;
                    foreach ($shifts as $shift) {
                        $sum_shift = Order::where('shift_id', $shift->id)->sum('total_amount');
                        $total_amount = $total_amount + $sum_shift;
                    }
                    $weekly_date = [
                        'date'   => Carbon::parse($shift_date)->format('D'),
                        'amount' => $total_amount + $sum_shift
                    ];
                    array_push($result, $weekly_date);
                }
            }
            return $result;
            $screen = "SelectWeeklySaleGraph report from ReportRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleGraph report from ReportRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }


    }

    public function weeklySaleExcel()
    {
        try {

        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleExcel report from ReportRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }


    }
}
