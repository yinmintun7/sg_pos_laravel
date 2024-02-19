<?php

namespace App\Repository\Order;

use App\Constant;
use App\Models\Category;
use App\Utility;
use App\Models\Item;
use App\Models\DiscountItem;
use App\Models\Order;
use App\ResponseStatus;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
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
            foreach ($data['items'] as $detail_item) {
                $insert_detail_data = [];
                $insert_detail_data['quantity']       = $detail_item['quantity'];
                $insert_detail_data['sub_total']      = $detail_item['amount'];
                $insert_detail_data['order_id']       = $store_order->id;
                $insert_detail_data['item_id']        = $detail_item['id'];
                $insert_detail_data['discount_price'] = $detail_item['original_discount'];
                $insert_detail_data['original_price'] = $detail_item['original_amount'];
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

    public function getEditOrder(int $id)
    {
        try {
            $order = Order::find($id);
            $screen   = "GetCategoryById From CategoryRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "GetCategoryById From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

}
