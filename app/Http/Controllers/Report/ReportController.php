<?php

namespace App\Http\Controllers\Report;

use App\Utility;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\Report\ReportRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;
use App\ResponseStatus;
use App\Http\Requests\GetDailyReportRequest;
use App\Exports\OrderDailyReport;

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
            $weekly = $this->ReportRepository->monthlySaleGraph();
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
}
