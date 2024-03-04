<?php

namespace App\Repository\Report;

use App\Utility;
use App\Constant;
use DateTime;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Shift;
use App\ResponseStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportRepository implements ReportRepositoryInterface
{
    public function weeklySaleGraph()
    {
        try {
            $dates = Utility::getLastSevenDay(null, null);
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

    public function getMonthlySale($start_month, $end_month)
    {
        try {
            $result = [];
            $month  = [];
            $total  = [];
            // $start_month = date('Y-m');
            // $end_month   = date('Y-m', strtotime($start_month .' - 7 months'));
            $dates = Utility::getLastSevenMonths($start_month, $end_month);
            foreach ($dates as $shift_month) {
                $shifts = Shift::where('start_date_time', 'LIKE', $shift_month . '%')->get();
                if ($shifts != null) {
                    $total_amount = 0;
                    foreach ($shifts as $shift) {
                        $sum_shift = Order::where('shift_id', $shift->id)->sum('total_amount');
                        $total_amount = $total_amount + $sum_shift;
                    }
                    array_push($month, Carbon::parse($shift_month)->format('M'));
                    array_push($total, $total_amount);
                }
            }
            $result = [
                'month' => $month,
                'total' => $total,
            ];
            return $result;
            $screen = "SelectMonthSaleGraph report from ReportRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "SelectMonthSaleGraph report from ReportRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function getDailyReport($start, $end)
    {
        try {
            $dates = Utility::getLastSevenDay($start, $end);
            $result = [];
            $all_total = 0;
            foreach ($dates as $shift_date) {
                $shifts = Shift::whereDate('start_date_time', $shift_date)->get();
                if ($shifts != null) {
                    $total_amount = 0;
                    foreach ($shifts as $shift) {
                        $sum_shift = Order::where('shift_id', $shift->id)->sum('total_amount');
                        $total_amount = $total_amount + $sum_shift;
                    }
                    $all_total = $all_total + $total_amount;

                    $weekly_date = (object) [
                        'date'   => Carbon::parse($shift_date)->format('Y-m-d'),
                        'amount' => $total_amount + $sum_shift,
                        'total'  => ''
                    ];
                    array_push($result, $weekly_date);
                }
            }
            $total_row = (object)[
                'date'   => '',
                'amount' => '',
                'total'  => $all_total
            ];
            array_push($result, $total_row);
            return $result;

        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleExcel report from ReportRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function dailyBestSellingList()
    {
        try {
            $result = [];
            $dates = Utility::getLastSevenDay(null, null);
            foreach ($dates as $shift_date) {
                $items = OrderDetail::leftJoin('item', 'item.id', 'order_detail.item_id')
                        ->whereDate('order_detail.created_at', $shift_date)
                        ->select('item.name', DB::raw('SUM(order_detail.quantity) as total_quantity'), DB::raw('SUM(order_detail.         sub_total) as total_sub_total'))
                        ->whereNull('item.deleted_at')
                        ->whereNull('order_detail.deleted_at')
                        ->groupBy('item.name')
                        ->orderBy('total_quantity', 'desc')
                        ->paginate(10);
                array_push($result, $items);
            }
            return $items;
            $screen = "getWeeklyBestSellingItems from ReportRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "getWeeklyBestSellingItems from ReportRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

}
