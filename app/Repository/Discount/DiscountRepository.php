<?php

namespace App\Repository\Discount;

use App\Constant;
use App\Models\DiscountItem;
use App\Models\DiscountPromotion;
use App\Utility;
use App\ResponseStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiscountRepository implements DiscountRepositoryInterface
{
    public function create(array $data)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            DB::beginTransaction();
            $itemIds = $data['item'];
            $insert_data = [];
            $insert_data['name']          = $data['name'];
            $insert_data['start_date']    =  Utility::changeFormatmdY2Ymd($data['start_date']);
            $insert_data['end_date']      =  Utility::changeFormatmdY2Ymd($data['end_date']);
            $insert_data['status']        = $data['status'];
            if ($data['discount_type'] == 'cash') {
                $insert_data['amount']    = $data['amount'];
            } else {
                $insert_data['percentage']    = $data['amount'];
            };
            $store = Utility::getCreateId((array)$insert_data);
            $create_discount = DiscountPromotion::create($store);
            $discount_id = $create_discount->id;
            $discount_items = [];
            foreach ($itemIds as $itemId) {
                $discount_items['item_id']  = $itemId;
                $discount_items['discount_id'] = $discount_id;
                $store_dis = Utility::getCreateId((array)$discount_items);
                DiscountItem::create($store_dis);
            }
            DB::commit();
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            DB::rollBack();
            $screen = "CreateDiscount From DiscountRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    // public function getCategory()
    // {
    //     try {
    //         $categories = [];
    //         $categories = Category::from('category as c')
    //         ->select('c.id', 'c.name', 'c.parent_id', 'c.status', 'c.image')
    //         ->leftJoin('category as p', 'c.parent_id', '=', 'p.id')
    //         ->whereNull('c.deleted_at')
    //         ->orderByDesc('c.id')
    //         ->addSelect(DB::raw('COALESCE(p.name, "None") as parent_name'))
    //         ->paginate(10);
    //         return $categories;
    //     } catch (\Exception $e) {
    //         $screen = "GetCategory From Category Form Screen::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }
    // }

    // public function getCategoryById(int $id)
    // {
    //     try {
    //         $category = Category::find($id);
    //         return $category;
    //         $screen   = "GetCategoryById From CategoryRepository::";
    //         $queryLog = DB::getQueryLog();
    //         Utility::saveDebugLog($screen, $queryLog);
    //     } catch (\Exception $e) {
    //         $screen = "GetCategoryById From CategoryRepository::";
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

    // public function deleteCategory($id)
    // {
    //     try {
    //         $delete_data = [];
    //         $delete = Category::find($id);
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