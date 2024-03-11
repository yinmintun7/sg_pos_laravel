<?php

namespace App\Repository\Shift;

use App\Constant;
use App\Models\Order;
use App\Models\Shift;
use App\ResponseStatus;
use App\Utility;
use Illuminate\Support\Facades\DB;

class ShiftRepository implements ShiftRepositoryInterface
{
    public function getShiftStart()
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $shift = Shift::select('id')
            ->whereNotNull('start_date_time')
            ->whereNull('end_date_time')
            ->whereNull('deleted_at')
            ->first();
            return $shift ? $shift->id : null;
        } catch (\Exception $e) {
            $screen = "getShiftStart From ShiftRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function hasUnpayOrder($shift)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $has_unpay_order = Order::select(DB::raw('count(id) as total'))
            ->where('status', 0)
            ->where('shift_id', $shift)
            ->whereNull('deleted_at')
            ->first();
            return $has_unpay_order->total;
        } catch (\Exception $e) {
            $screen = "Shiftend From ShiftRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
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
            $screen = "ShiftStart From ItemRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function end()
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $today_date = date('Y-m-d H:i:s');
            $data = [];
            $data = ['end_date_time' => $today_date];
            $update_data = Utility::getUpdateId((array)$data);
            Shift::whereNull('end_date_time')->update($update_data);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "ShiftEnd From ItemRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function getShiftList()
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $shift_list = [];
            $shift_list = Shift::select('id', 'start_date_time', 'end_date_time')
                ->where('status', '=', Constant::ENABLE_STATUS)
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->paginate(10);
            return $shift_list;
        } catch (\Exception $e) {
            $screen = "getShiftList From ItemRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }
}
