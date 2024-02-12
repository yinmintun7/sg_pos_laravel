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
            $insert_data['image'] = $unique_name;
            $store = Utility::getCreateId((array)$insert_data);
            Setting::create($store);
            $destination_path = storage_path('/app/public/upload/setting/');
            Utility::cropResize($file, $destination_path, $unique_name);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "CreateDiscount From DiscountRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    // public function getDiscount()
    // {
    //     try {
    //         $discount_items = [];
    //         $discount_items = DiscountPromotion::select('id', 'name', DB::raw("CASE WHEN amount IS NULL THEN CONCAT(percentage, '%') ELSE CONCAT(amount, ' kyats') END as discount_amount"), 'start_date', 'end_date', 'description', 'status')
    //                     ->whereNull('deleted_at')
    //                     ->orderByDesc('id')
    //                     ->paginate(10);

    //         return $discount_items;
    //     } catch (\Exception $e) {
    //         $screen = "GetCategory From Category Form Screen::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }
    // }

    // public function getDiscountById(int $id)
    // {
    //     try {
    //         $discount_item = DiscountPromotion::find($id);
    //         return $discount_item;
    //         $screen   = "GetDiscountById From DiscountRepository::";
    //         $queryLog = DB::getQueryLog();
    //         Utility::saveDebugLog($screen, $queryLog);
    //     } catch (\Exception $e) {
    //         $screen = "GetDiscountById From DiscountRepository::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }
    // }

    // public function updateCategory($request)
    // {
    //     try {
    //         $id = $request['id'];
    //         $name = $request['name'];
    //         $parent_id = $request['parent_id'];
    //         $status = $request['status'];
    //         $update_data = [];
    //         $update = Category::find($id);
    //         if (array_key_exists('image', $request)) {
    //             $name_without_extension = pathinfo($request['image']->getClientOriginalName(), PATHINFO_FILENAME);
    //             $extension = $request['image']->getClientOriginalExtension();
    //             $unique_name = $name_without_extension . '-' . now()->format('Y-m-d_His') . '-' . uniqid() . '.' . $extension;
    //             $destination_path = storage_path('/app/public/upload/category/' . $id);
    //             Utility::cropResize($request['image'], $destination_path, $unique_name);
    //             $update_data['image'] = $unique_name;
    //             $old_image     = storage_path('/app/public/upload/category/' . $id.'/'.$update->image);
    //             unlink($old_image);

    //         }
    //         $update_data['name']      = $name;
    //         $update_data['parent_id'] = $parent_id;
    //         $update_data['status']    = $status;
    //         $confirm_update = Utility::getUpdateId((array)$update_data);
    //         $update->update($confirm_update);
    //         $screen   = "UpdateCategory From Category Form Screen::";
    //         $queryLog = DB::getQueryLog();
    //         Utility::saveDebugLog($screen, $queryLog);
    //         $returnArray['ResponseStatus'] = ResponseStatus::OK;
    //         return $returnArray;

    //     } catch (\Exception $e) {
    //         $screen = "UpdateCategory From CategoryRepository::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }
    // }

    // public function delete($id)
    // {
    //     try {
    //         $delete_data = [];
    //         $delete = DiscountPromotion::find($id);
    //         $confirm_delete = Utility::getDeletedId((array)$delete_data);
    //         $delete->update($confirm_delete);

    //         $returnArray['ResponseStatus'] = ResponseStatus::OK;
    //         return $returnArray;
    //     } catch (\Exception $e) {
    //         $screen = "DeleteCategory From CategoryRepository::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }
    // }
}
