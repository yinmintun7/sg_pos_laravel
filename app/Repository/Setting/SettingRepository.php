<?php

namespace App\Repository\Setting;

use App\Constant;
use App\Models\DiscountItem;
use App\Models\Setting;
use App\Utility;
use App\ResponseStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingRepository implements SettingRepositoryInterface
{
    public function create(array $data)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $insert_data = [];
            $insert_data['company_name']      = $data['company_name'];
            $insert_data['company_phone']     = $data['company_phone'];
            $insert_data['company_email']     = $data['company_email'];
            $insert_data['company_address']   = $data['company_address'];
            $insert_data['company_email']     = $data['company_email'];
            $insert_data['status']            = $data['status'];
            $file = $data['company_logo'];
            $name_without_extension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $unique_name = $name_without_extension . '-' . now()->format('Y-m-d_His') . '-' . uniqid() . '.' . $extension;
            $insert_data['company_logo'] = $unique_name;
            $store = Utility::getCreateId((array)$insert_data);
            Setting::create($store);
            $destination_path = storage_path('/app/public/upload/setting/');
            Utility::cropResize($file, $destination_path, $unique_name);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "CreateSetting From SettingRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function getSetting()
    {
        try {
            $setting = Setting::select('id', 'company_name', 'company_phone', 'company_email', 'company_address', 'company_logo', 'status')
            ->whereNull('deleted_at')
            ->first();
            return $setting;
        } catch (\Exception $e) {
            $screen = "GetSetting From SettingRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function update($request)
    {
        try {
            $id = $request['id'];
            $company_name    = $request['company_name'];
            $company_phone   = $request['company_phone'];
            $company_email   = $request['company_email'];
            $company_address = $request['company_address'];
            $status          = $request['status'];
            $update_data = [];
            $update = Setting::find($id);
            if (array_key_exists('company_logo', $request)) {
                $name_without_extension = pathinfo($request['company_logo']->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $request['company_logo']->getClientOriginalExtension();
                $unique_name = $name_without_extension . '-' . now()->format('Y-m-d_His') . '-' . uniqid() . '.' . $extension;
                $destination_path = storage_path('/app/public/upload/setting/');
                Utility::cropResize($request['company_logo'], $destination_path, $unique_name);
                $update_data['company_logo'] = $unique_name;
                $old_image     = storage_path('/app/public/upload/setting/'.$update->company_logo);
                unlink($old_image);
            }
            $update_data['company_name']    = $company_name;
            $update_data['company_phone']   = $company_phone;
            $update_data['company_email']   = $company_email;
            $update_data['company_address'] = $company_address;
            $update_data['status']          = $status;
            $confirm_update = Utility::getUpdateId((array)$update_data);
            $update->update($confirm_update);
            $screen   = "UpdateSetting From SettingRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "UpdateSetting From SettingRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function delete($id)
    {
        try {
            $delete_data = [];
            $delete = Setting::find($id);
            $confirm_delete = Utility::getDeletedId((array)$delete_data);
            $delete->update($confirm_delete);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "DeleteSetting From SettingRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
