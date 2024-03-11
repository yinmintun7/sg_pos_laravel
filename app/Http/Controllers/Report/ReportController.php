<?php

namespace App\Http\Controllers\Report;

use App\Exports\MonthlyBestSellingReport;
use App\Exports\OrderDailyReport;
use App\Exports\OrderMonthlyReport;
use App\Exports\WeeklyBestSellingReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetDailyReportRequest;
use App\Http\Requests\GetMonthlyReportRequest;
use App\Repository\Report\ReportRepositoryInterface;
use App\Utility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private $ReportRepository;
    public function __construct(ReportRepositoryInterface $ReportRepository)
    {
        DB::connection()->enableQueryLog();
        $this->ReportRepository = $ReportRepository;
    }

    public function weeklySaleGraph()
    {
        try {
            $weekly = $this->ReportRepository->weeklySaleGraph();
            return new JsonResponse($weekly);
            $screen = "SelectWeeklySaleGraph report from ReportController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleGraph report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function dailyReportExcel(OrderDailyReport $orderDailyReport, GetDailyReportRequest $request)
    {
        try {
            $start = isset($request['start_date']) ? $request['start_date'] : null;
            $end   =  isset($request['end_date']) ? $request['end_date'] : null;
            return Excel::download($orderDailyReport->setRange($start, $end), 'weekly.xlsx');
            $screen = "SelectWeeklySaleExcel report from ReportController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleExcel report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function dailyReportTable(GetDailyReportRequest $request)
    {
        try {
            $start = isset($request['start_date']) ? $request['start_date'] : null;
            $end   =  isset($request['end_date']) ? $request['end_date'] : null;
            $result = $this->ReportRepository->getDailyReport($start, $end);
            return view('backend.report.daily', compact(['result']));
        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleExcel report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function monthlySaleGraph()
    {
        try {
            $start_month  = isset($request['start_month']) ? $request['start_date'] : date('Y-m');
            $end_month    = isset($request['end_month']) ? $request['end_date'] : date('Y-m', strtotime(date('Y-m') .' - 7 months'));
            $monthly_data = $this->ReportRepository->getMonthlySale($start_month, $end_month);
            return new JsonResponse($monthly_data);
            $screen = "SelectMonthlySaleGraph report from ReportController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "SelectMonthlySaleGraph report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function monthlyReportTable(GetMonthlyReportRequest $request)
    {
        try {
            $start_month  = (isset($request->start_month)) ? $request->start_month : date('Y-m');
            $end_month    = (isset($request->end_month)) ? $request->end_month : date('Y-m', strtotime(date('Y-m') .' - 7 months'));
            $result       = $this->ReportRepository->getMonthlySale($start_month, $end_month);
            return view('backend.report.monthly', compact(['result']));
            $screen = "SelectMonthlySale report from ReportController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "SelectMonthlySale report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function monthlyReportExcel(OrderMonthlyReport $orderMonthlyReport, GetMonthlyReportRequest $request)
    {
        try {
            $start_month  = (isset($request->start_month)) ? $request->start_month : date('Y-m');
            $end_month    = (isset($request->end_month)) ? $request->end_month : date('Y-m', strtotime(date('Y-m') .' - 7 months'));
            $name    = date('Ymdhis').'_'.'monthly_report.xlsx';
            return Excel::download($orderMonthlyReport->setRange($start_month, $end_month), $name);
            $screen = "DownloadMonthlySaleExcel report from ReportController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "DownloadMonthlySaleExcel report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function dailyBestSellingList(Request $request)
    {
        try {
            $start_date  = (isset($request->start_date)) ? $request->start_date : null;
            $end_date    = (isset($request->end_date)) ? $request->end_date : null;
            $result = $this->ReportRepository->dailyBestSellingList($start_date, $end_date);
            return view('backend.report.daily_best_selling', compact(['result']));
            $screen = "getWeeklyBestSellingItems from ReportController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "getWeeklyBestSellingItems report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function dailyBestSellingExcel(WeeklyBestSellingReport $weeklyBestSelling)
    {
        try {
            $name  = date('Ymdhis').'_'.'daily_best_selling.xlsx';
            return Excel::download($weeklyBestSelling->setRange(), $name);
            $screen = "DownloadMonthlySaleExcel report from ReportController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "DownloadMonthlySaleExcel report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function monthlyBestSellingList(Request $request)
    {
        try {
            $month  = (isset($request->month)) ? $request->month : date('m-Y');
            $result = $this->ReportRepository->monthlyBestSellingList($month);
            return view('backend.report.monthly_best_selling', compact(['result']));
            $screen = "getWeeklyBestSellingItems from ReportController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "getWeeklyBestSellingItems report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function monthlyBestSellingExcel(Request $request, MonthlyBestSellingReport $monthlyBestSelling)
    {
        try {
            $today_month = "'".date('m/Y')."'";
            $month  = (isset($request->month)) ? $request->month : $today_month;
            $name  = date('Ymdhis').'_'.'monthly_best_selling.xlsx';
            return Excel::download($monthlyBestSelling->setRange($month), $name);
            $screen = "DownloadMonthlySaleExcel report from ReportController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "DownloadMonthlySaleExcel report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function paymentHistory($date)
    {
        try {
            $result = $this->ReportRepository->paymentHistory($date);
            return view('backend.report.', compact(['result']));
        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleExcel report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
