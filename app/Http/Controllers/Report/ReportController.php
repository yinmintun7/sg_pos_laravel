<?php

namespace App\Http\Controllers\Report;

use App\Utility;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\Report\ReportRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\ResponseStatus;

class ReportController extends Controller
{
    private $ReportRepository;
    public function __construct(ReportRepositoryInterface $ReportRepository)
    {
        DB::connection()->enableQueryLog();
        $this->ReportRepository = $ReportRepository;
    }

    public function weekly(){
            try {
                $weekly = $this->ReportRepository->weeklySale();
                dd($weekly);
                return new JsonResponse([
                    'success' => true,
                    'message' => 'success store order',
                    'status'  => ResponseStatus::OK,
                ]);
                $screen = "Shift Open From Shift Index Screen::";
                $queryLog = DB::getQueryLog();
                Utility::saveDebugLog($screen, $queryLog);
            } catch (\Exception $e) {
                $screen = "Shift Open From Shift Index Screen::";
                Utility::saveErrorLog($screen, $e->getMessage());
                abort(500);
            }
        }
    }

