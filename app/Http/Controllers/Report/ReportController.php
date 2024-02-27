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
use App\Exports\OrderWeeklyReport;

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

    public function weeklySaleExcel()
    {
        try {
            // $weekly = $this->ReportRepository->weeklySaleExcel();
            return Excel::download(new OrderWeeklyReport(), 'weekly.xlsx');
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
