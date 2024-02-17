<?php

namespace App\Repository\Item;

use App\Constant;
use App\Models\Item;
use App\Models\Category;
use App\Models\DiscountItem;
use App\Utility;
use App\ResponseStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class ItemRepository implements ItemRepositoryInterface
{
    public function create(array $data)
    {

        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $insert_data = [];
            $insert_data['name']        = $data['name'];
            $insert_data['category_id'] = $data['category_id'];
            $insert_data['price']       = $data['price'];
            $insert_data['quantity']    = $data['quantity'];
            $insert_data['status']      = $data['status'];
            $file = $data['image'];
            $name_without_extension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $unique_name = $name_without_extension . '-' . now()->format('Y-m-d_His') . '-' . uniqid() . '.' . $extension;
            $insert_data['image'] = $unique_name;
            $store = Utility::getCreateId((array)$insert_data);
            $create_item = Item::create($store);
            $code_key = '';
            for ($i = 0; $i <= 3; $i++) {
                $code_key .= chr(rand(65, 90));
            }
            $code_no = $create_item->category_id . $create_item->id . '-' . $code_key;
            $insert_codeno = Item::find($create_item->id);
            $confirm_codeno['code_no'] = $code_no;
            $insert_codeno->update($confirm_codeno);
            $destination_path = storage_path('/app/public/upload/item/' . $create_item->id);
            Utility::cropResize($file, $destination_path, $unique_name);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "ItemCreate From ItemRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function getItems($api = false)
    {
        try {
            $items = [];
            $items = Item::select('id', 'name', 'price', 'quantity', 'code_no', 'category_id', 'status', 'image')
            ->from('item')
            ->whereNull('deleted_at')
            ->orderByDesc('id');
            if ($api == true) {
                $items->where('status', Constant::ENABLE_STATUS);
                $items = $items->get();
            } else {
                $items = $items->paginate(10);
            }
            return $items;
        } catch (\Exception $e) {
            $screen = "GetItems From ItemRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function getItemByCategory($category_id, $api = false)
    {

        try {
            $items = [];
            $items = Item::select('id', 'name', 'price', 'quantity', 'code_no', 'category_id', 'status', 'image')
            ->from('item')
            ->whereNull('deleted_at')
            ->orderByDesc('id')
            ->where('category_id', $category_id);
            if ($api == true) {
                $items->where('status', Constant::ENABLE_STATUS);
                $items = $items->get();
            } else {
                $items = $items->paginate(10);
            }

            return $items;
        } catch (\Exception $e) {
            $screen = "GetItems From ItemRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function getOrderItemById(int $item_id)
    {

        try {
            $today_date = date('Y-m-d');
            $item = Item::where('id', $item_id)
            ->where('status', Constant::ENABLE_STATUS)
            ->whereNull('deleted_at')
            ->select('id', 'name', 'category_id', 'image', 'price', 'code_no')
            ->first();
            $discount = DiscountItem::select(DB::raw('CAST(
                                CASE
                                WHEN dp.amount IS NULL AND dp.percentage IS NOT NULL THEN (i.price * dp.percentage / 100)
                                WHEN dp.amount IS NOT NULL AND dp.percentage IS NULL THEN dp.amount
                            END
                            AS UNSIGNED) AS total_discount'))
                       ->leftJoin('discount_promotion as dp', 'discount_item.discount_id', '=', 'dp.id')
                       ->leftJoin('item as i', 'discount_item.item_id', '=', 'i.id')
                       ->where('dp.start_date', '<=', $today_date)
                       ->where('dp.end_date', '>=', $today_date)
                       ->whereNull('dp.deleted_at')
                       ->whereNull('discount_item.deleted_at')
                       ->where('i.id', $item_id)
                       ->first();
            $total_discount          = ($discount != null) ? $discount->total_discount : 0;
            $item->discount          = $total_discount;
            $item->original_discount = $total_discount;
            $item->amount            = $item->price - $total_discount;
            $item->original_amount   = $item->price - $total_discount;
            return $item;
        } catch (\Exception $e) {
            $screen = "GetItemData From ItemRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function updateItem($request)
    {
        try {
            $id = $request['id'];
            $name = $request['name'];
            $category_id = $request['category_id'];
            $price = $request['price'];
            $quantity = $request['quantity'];
            $status = $request['status'];
            $update_data = [];
            $update = Item::find($id);
            if (array_key_exists('image', $request)) {
                $name_without_extension = pathinfo($request['image']->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $request['image']->getClientOriginalExtension();
                $unique_name = $name_without_extension . '-' . now()->format('Y-m-d_His') . '-' . uniqid() . '.' . $extension;
                $destination_path = storage_path('/app/public/upload/item/' . $id);
                Utility::cropResize($request['image'], $destination_path, $unique_name);
                $update_data['image'] = $unique_name;
                $old_image     = storage_path('/app/public/upload/item/' . $id.'/'.$update->image);
                unlink($old_image);

            }
            $update_data['name']        = $name;
            $update_data['category_id'] = $category_id;
            $update_data['price']       = $price;
            $update_data['quantity']    = $quantity;
            $update_data['status']      = $status;
            $confirm_update = Utility::getUpdateId((array)$update_data);
            $update->update($confirm_update);
            $screen   = "UpdateItem From ItemRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "UpdateItem From ItemRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function deleteItem($id)
    {
        try {
            $delete_data = [];
            $delete = Item::find($id);
            $confirm_delete = Utility::getDeletedId((array)$delete_data);
            $delete->update($confirm_delete);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "DeleteItem From ItemRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
