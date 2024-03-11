<?php

namespace App\Repository\Report;

use App\Models\Item;
use App\Models\Order;
use App\Models\Shift;
use App\ResponseStatus;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportRepositoryInterface
{
    public function weeklySaleGraph()
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
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
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function getMonthlySale($start_month, $end_month)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $result = [];
            $month  = [];
            $total  = [];
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
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function getDailyReport($start, $end)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
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
            $screen = "SelectWeeklySaleExcel report from ReportRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleExcel report from ReportRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }

    }

    public function dailyBestSellingList()
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $result = [];
            $dates = Utility::getLastSevenDay(null, null);
            foreach ($dates as $shift_date) {
                $items = Item::select('item.id', 'item.name')
                            ->leftJoin(DB::raw('(SELECT
                                                    item_id,
                                                    SUM(original_price * quantity) AS total_sum_price,
                                                    SUM(quantity) AS total_sum_quantity
                                                FROM
                                                    order_detail
                                                WHERE
                                                    created_at BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()
                                                GROUP BY
                                                    item_id
                                                ) AS T02'), 'item.id', '=', 'T02.item_id')
                            ->orderByDesc('T02.total_sum_quantity')
                            ->get();
            }
            return $items;
            $screen = "getWeeklyBestSellingItems from ReportRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "getWeeklyBestSellingItems from ReportRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function monthlyBestSellingList($month)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $result = [];
            $month = '03/2024';
            $results = Item::select('item.id', 'item.name')
                            ->leftJoin(DB::raw('(SELECT
                                        item_id,
                                        SUM(original_price * quantity) AS total_sum_price,
                                        SUM(quantity) AS total_sum_quantity
                                    FROM
                                        order_detail
                                    WHERE
                                        DATE_FORMAT(created_at, "%m/%Y") = ?
                                    GROUP BY
                                        item_id
                                    ) AS T02'), 'item.id', '=', 'T02.item_id')
                            ->orderByDesc('T02.total_sum_quantity')
                            ->setBindings([$month])
                            ->get();
            return $results;
            $screen = "getMonthlyBestSellingItems from ReportRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "getMonthlyBestSellingItems from ReportRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function paymentHistory($date)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            // foreach ($dates as $shift_date) {
            $shift_ids = Shift::whereDate('start_date_time', $date)->get('id');
            //     if ($shifts != null) {
            //         $total_amount = 0;
            //         foreach ($shifts as $shift) {
            //             $sum_shift = Order::where('shift_id', $shift->id)->sum('total_amount');
            //             $total_amount = $total_amount + $sum_shift;
            //         }
            //         $all_total = $all_total + $total_amount;

            //         $weekly_date = (object) [
            //             'date'   => Carbon::parse($shift_date)->format('Y-m-d'),
            //             'amount' => $total_amount + $sum_shift,
            //             'total'  => ''
            //         ];
            //         array_push($result, $weekly_date);
            //     }
            // }
        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleExcel report from ReportRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }
}
