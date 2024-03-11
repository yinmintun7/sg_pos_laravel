<?php

namespace App\Repository\Discount;

use App\Constant;
use App\Models\DiscountItem;
use App\Models\DiscountPromotion;
use App\ResponseStatus;
use App\Utility;
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
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function getDiscount()
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $discount_items = [];
            $discount_items = DiscountPromotion::select('id', 'name', DB::raw("CASE WHEN amount IS NULL THEN CONCAT(percentage, '%') ELSE CONCAT(amount, ' kyats') END as discount_amount"), 'start_date', 'end_date', 'description', 'status')
                        ->whereNull('deleted_at')
                        ->orderByDesc('id')
                        ->paginate(10);
            return $discount_items;
        } catch (\Exception $e) {
            $screen = "GetCategory From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function getDiscountById(int $id)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $discount_item = DiscountPromotion::find($id);
            return $discount_item;
        } catch (\Exception $e) {
            $screen = "GetDiscountById From DiscountRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function getItemByDiscountId(int $id)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $data = [];
            $discount_items = DiscountItem::SELECT("item_id")
                          ->where('discount_id', $id)
                          ->whereNull('deleted_at')
                          ->where('status', Constant::ENABLE_STATUS)
                          ->get();
            foreach ($discount_items as $item) {
                array_push($data, $item->item_id);
            }
            return $data;
        } catch (\Exception $e) {
            $screen = "getItemByDiscountId From DiscountRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function update($request)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            DB::beginTransaction();
            $update_data = [];
            $itemIds = $request['item'];
            $id = $request['id'];
            $update_data['name']          = $request['name'];
            $update_data['start_date']    =  Utility::changeFormatmdY2Ymd($request['start_date']);
            $update_data['end_date']      =  Utility::changeFormatmdY2Ymd($request['end_date']);
            $update_data['status']        = $request['status'];
            if ($request['discount_type'] == 'cash') {
                $update_data['amount']    = $request['amount'];
            } else {
                $update_data['percentage']    = $request['amount'];
            };
            $update_data['description']          = $request['description'];

            $update = DiscountPromotion::find($id);
            $confirm_update = Utility::getUpdateId((array)$update_data);
            $update->update($confirm_update);
            DiscountItem::where('discount_id', $id)->delete();
            $discount_items = [];
            foreach ($itemIds as $itemId) {
                $discount_items['item_id']  = $itemId;
                $discount_items['discount_id'] = $id;
                $store_dis = Utility::getCreateId((array)$discount_items);
                DiscountItem::create($store_dis);
            }
            DB::commit();
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            DB::rollBack();
            $screen = "UpdateCategory From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function delete($id)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            DB::beginTransaction();
            $delete_data = [];
            $delete = DiscountPromotion::find($id);
            $confirm_delete = Utility::getDeletedId((array)$delete_data);
            $delete->update($confirm_delete);
            $delete_dis = [];
            $delete_dis_item = DiscountItem::where('discount_id', $id);
            $confirm_delete = Utility::getDeletedId((array)$delete_dis);
            $delete_dis_item->update($confirm_delete);
            DB::commit();
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            DB::rollBack();
            $screen = "DeleteCategory From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }
}
