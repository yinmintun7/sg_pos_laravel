<?php

namespace App\Http\Controllers\Report;

use App\Utility;
use App\ResponseStatus;
use Illuminate\Http\Request;
use App\Exports\OrderDailyReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\GetDailyReportRequest;
use App\Http\Requests\GetMonthlyReportRequest;
use App\Repository\Report\ReportRepositoryInterface;

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

    public function monthlySaleGraph()
    {
        try {
            $start_month  = isset($request['start_month']) ? $request['start_date'] : date('Y-m');
            $end_month    = isset($request['end_month']) ? $request['end_date'] : date('Y-m', strtotime(date('Y-m') .' - 7 months'));
            $monthly_data = $this->ReportRepository->monthlySaleGraph( $start_month, $end_month);
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

    public function dailyReportExcel(OrderDailyReport $orderDailyReport, GetDailyReportRequest $request)
    {
        try {
            $start = isset($request['start_date']) ? $request['start_date'] : null;
            $end   =  isset($request['end_date']) ? $request['end_date'] : null;
            $result = $orderDailyReport->setRange($start, $end);
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
            return view('backend.report.index', compact(['result']));
        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleExcel report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function monthlyReportTable(GetMonthlyReportRequest $request)
    {
        try {
            $start_month  = isset($request['start_month']) ? $request['start_date'] : date('Y-m');
            $end_month    = isset($request['end_month']) ? $request['end_date'] : date('Y-m', strtotime(date('Y-m') .' - 7 months'));
            $result = $this->ReportRepository->getMonthlySaleReport($start_month, $end_month);
            return view('backend.report.index', compact(['result']));
        } catch (\Exception $e) {
            $screen = "SelectWeeklySaleExcel report from ReportController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function monthlyReportExcel(OrderDailyReport $orderDailyReport, GetMonthlyReportRequest $request)
    {
        try {
            $start = isset($request['start_date']) ? $request['start_date'] : null;
            $end   =  isset($request['end_date']) ? $request['end_date'] : null;
            $result = $orderDailyReport->setRange($start, $end);
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

}
