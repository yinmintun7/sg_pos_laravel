<?php

namespace App\Http\Controllers\Shift;

use App\Utility;
use App\ResponseStatus;
use App\Http\Controllers\Controller;
use App\Repository\Shift\ShiftRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    private $ShiftRepository;
    public function __construct(ShiftRepositoryInterface $ShiftRepository)
    {
        DB::connection()->enableQueryLog();
        $this->ShiftRepository = $ShiftRepository;
    }

    public function index()
    {
        try {
            $shift_open = false;
            $shift = $this->ShiftRepository->getShiftStart();
            if ($shift > 0) {
                $shift_open = true;
            }
            $shift_list = $this->ShiftRepository->getShiftList();
            $screen = "Shift Index Screen::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.shift.index', compact(['shift_open','shift_list']));
        } catch (\Exception $e) {
            $screen = "Shift Index Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function start()
    {
        try {
            $shift = $this->ShiftRepository->getShiftStart();
            if ($shift  <= 0) {
                $shift_start = $this->ShiftRepository->start();
                if ($shift_start['ResponseStatus'] == ResponseStatus::OK) {
                    return redirect()->back()->with(['shift_start' => 'Success!,Shift is starting now!']);
                } else {
                    return redirect()->back()->withErrors(['shift_error' => 'Cannot start Shift ,Somethine is wrong!']);
                }
            } else {
                return redirect()->back()->withErrors(['shift_error' => 'Fail!,Shift is already start!']);
            }
            $screen = "Shift Open From Shift Index Screen::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "Shift Open From Shift Index Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function end()
    {
        try {
            $shift = $this->ShiftRepository->getShiftStart();
            if ($shift > 0) {
                $has_unpay_order = $this->ShiftRepository->hasUnpayOrder((int)$shift);
                if($has_unpay_order > 0){
                    return redirect()->back()->withErrors(['fail' => 'Cannot end Shift while all orders are not paied!']);
                }else{
                    $shift_end =  $this->ShiftRepository->end();
                    if ($shift_end['ResponseStatus'] == ResponseStatus::OK) {
                        return redirect()->back()->with(['success' => 'Success!,Shift is closed now!']);
                    } else {
                        return redirect()->back()->withErrors(['fail' => 'Cannot end Shift ,Something is wrong!']);
                    }
                }
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Shift is already end']);
            }
            $screen = "ShiftClose From ShiftController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "ShiftClose From ShiftController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function redirectTo404()
    {
        abort(404);
    }

    public function shiftClose()
    {
        try {
            return view('frontend.shift_close');
        } catch (\Exception $e) {
            $screen = "Shift Index Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
