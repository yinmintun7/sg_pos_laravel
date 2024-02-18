<?php

namespace App\Repository\Item;

use App\Constant;
use App\Models\Item;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\DiscountItem;
use App\Models\OrderDetail;
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

    public function storeOrderItems(array $data)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        DB::beginTransaction();
        try {
            $insert_data = [];
            $insert_data['total_amount'] = $data['subTotal'];
            $insert_data['shift_id']     = $data['shift_id'];
            $store = Utility::getCreateId((array)$insert_data);
            $store_order = Order::create($store);
            // dd($data['items']);
            foreach ($data['items'] as $detail_item){
                $insert_detail_data = [];
                $insert_detail_data['quantity']       = $detail_item['quantity'];
                $insert_detail_data['sub_total']      = $detail_item['amount'];
                $insert_detail_data['order_id']       = $store_order->id;
                $insert_detail_data['item_id']        = $detail_item['id'];
                $insert_detail_data['discount_price'] = $detail_item['original_discount'];
                $insert_detail_data['orginal_price']  = $detail_item['original_amount'];
                $store = Utility::getCreateId((array)$insert_detail_data);
                OrderDetail::create($store);
            }
            DB::commit();
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            DB::rollBack();
            $screen = "StoreOrder From ItemRepository::";
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

    public function getOrderList($shift_id)
    {
        try {
            $currentDate = date("Y-m-d");
            $order_list = [];
            $order_list = Order::select('id', 'created_at', 'status', 'total_amount')
            ->selectRaw("CONCAT('$shift_id', '-', id, DATE_FORMAT(created_at, '%y%m%d')) AS order_no")
            ->selectRaw("TIME_FORMAT(created_at, '%H:%i') AS order_time")
            ->where('shift_id', $shift_id)
            ->whereNull('deleted_at')
            ->orderBy('status', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();
        return $order_list;
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
            $item->quantity          = 1;
            $item->amount            = $item->price - $total_discount;
            $item->original_amount   = $item->price;
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
    public function CancelOrder(array $request)
    {
        try {
            $cacel_order = [];
            $id     = $request['corderId'];
            $status = $request['status'];
            $cancel = Order::find($id);
            $cacel_order['status']      = $status;
            $order_cancel = Utility::getUpdateId((array)$cacel_order);
            $cancel->update($order_cancel);
            $screen   = "CancelOrder From ItemRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "CancelOrder From ItemRepository::";
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
