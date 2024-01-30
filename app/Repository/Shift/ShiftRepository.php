<?php

namespace App\Repository\Shift;

use App\Constant;
use App\Models\Shift;
use App\Utility;
use App\ResponseStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShiftRepository implements ShiftRepositoryInterface
{
    public function getShiftStart()
    {
        $shift = Shift::select(DB::raw('count(id) as total'))
            ->whereNotNull('start_date_time')
            ->whereNull('end_date_time')
            ->whereNull('deleted_at')
            ->first();
        return $shift->total;
    }

    public function start()
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $data = [];
            $today_date = date('Y-m-d H:i:s');
            $data = ['start_date_time' => $today_date];
            $insert_data = Utility::getCreateId((array)$data);
            Shift::create($insert_data);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function end()
    {
        try {
            $today_date = date('Y-m-d H:i:s');
            $data = [];
            $data = ['end_date_time' => $today_date];
            $update_data = Utility::getUpdateId((array)$data);
            $update = Shift::whereNull('end_date_time')->update($update_data);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function getShiftList()
    {
        try {
            $shift_list = [];
            $shift_list = Shift::select('id', 'start_date_time', 'end_date_time')
                ->where('status', '=', Constant::ENABLE_STATUS)
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->paginate(10);
            return $shift_list;
        } catch (\Exception $e) {
            abort(500);
        }
    }
}
