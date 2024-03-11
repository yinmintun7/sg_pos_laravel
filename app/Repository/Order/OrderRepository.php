<?php

namespace App\Repository\Order;

use App\Constant;
use App\Models\DiscountItem;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentHistory;
use App\Models\Setting;
use App\ResponseStatus;
use App\Utility;
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
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function getOrderList($shift_id)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
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
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function getOrderItemById(int $item_id)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
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
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function CancelOrder(array $request)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $cacel_order = [];
            $id     = $request['corderId'];
            $status = $request['status'];
            $cancel = Order::find($id);
            $cacel_order['status']      = $status;
            $order_cancel = Utility::getUpdateId((array)$cacel_order);
            $cancel->update($order_cancel);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "CancelOrder From ItemRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function getOrderItems(int $id)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $item_ids = [];
            $detail_items = OrderDetail::select('item_id')
                        ->where('order_id', $id)
                        ->where('status', Constant::ENABLE_STATUS)
                        ->whereNull('deleted_at')
                        ->get();
            foreach ($detail_items as $item) {
                array_push($item_ids, $item->item_id);
            }
            $data = [];
            $items = Item::select(
                'item.id',
                'item.name',
                'item.category_id',
                'item.image',
                'item.price',
                'item.code_no',
                'order_detail.quantity'
            )
                        ->where('order_detail.order_id', '=', $id)
                        ->leftJoin('order_detail', 'order_detail.item_id', '=', 'item.id')
                        ->whereIn('item.id', $item_ids)
                        ->where('item.status', Constant::ENABLE_STATUS)
                        ->whereNull('item.deleted_at')
                        ->get();
            foreach ($items as $item) {
                $today_date = date('Y-m-d');
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
                        ->where('i.id', $item->id)
                        ->first();
                $total_discount          = ($discount != null) ? $discount->total_discount : 0;
                $item->discount          = $total_discount;
                $item->original_discount = $total_discount;
                $item->quantity          = $item->quantity;
                $item->amount            = $item->price - $total_discount;
                $item->original_amount   = $item->price;
                array_push($data, $item);
            }
            return $data;
        } catch (\Exception $e) {
            $screen = "GetCategoryById From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function updateOrder(array $data)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            DB::beginTransaction();
            $update_data = [];
            $id                          = $data['id'];
            $update_data['total_amount'] = $data['subTotal'];
            $update_data['shift_id']     = $data['shift_id'];
            $update_order   = Order::find($id);
            $confirm_update = Utility::getUpdateId((array)$update_data);
            $update_order->update($confirm_update);
            OrderDetail::where('order_id', $id)->delete();
            $order_detail = [];
            foreach ($data['items'] as $detail_item) {
                $order_detail['quantity']       = $detail_item['quantity'];
                $order_detail['sub_total']      = $detail_item['amount'];
                $order_detail['order_id']       = $id;
                $order_detail['item_id']        = $detail_item['id'];
                $order_detail['discount_price'] = $detail_item['original_discount'];
                $order_detail['original_price'] = $detail_item['original_amount'];
                $store = Utility::getCreateId((array)$order_detail);
                OrderDetail::create($store);
            }
            DB::commit();
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            DB::rollBack();
            $screen = "UpdateOrder From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function getOrderDetail(array $data)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $shift_id = $data['shift_id'];
            $id       = $data['orderId'];
            $order = Order::select('id', 'created_at', 'total_amount', 'status')
            ->selectRaw("CONCAT($shift_id, '-', id, DATE_FORMAT(created_at, '%y%m%d')) AS order_no")
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();
            return $order;
        } catch (\Exception $e) {
            $screen = "GetCategoryById From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }

    public function insertPayOrder(array $data)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            DB::beginTransaction();
            $order_id = $data['id'];
            $order_no = $data['order_no'];
            $refund   = $data['refund'];
            $customer_pay_amount = $data['customer_pay_amount'];
            $kyats = $data['kyats'];
            $update_order = [];
            $update_order = Order::find($order_id);
            $update_order['status']  = Constant::PAID_STATUS;
            $update_order['payment'] = $customer_pay_amount;
            $update_order['refund']  = $refund;
            $confirm_update = Utility::getUpdateId((array)$update_order);
            $update_order->update($confirm_update);
            foreach ($kyats as $kyat) {
                $insert_data = [];
                $cash = $kyat['cash'];
                $quantity = $kyat['quantity'];
                $insert_data['order_id']  = $order_id;
                $insert_data['code_no']   = $order_no;
                $insert_data['cash']      = $cash;
                $insert_data['quantity']  = $quantity;
                $store = Utility::getCreateId((array)$insert_data);
                PaymentHistory::create($store);
            }
            DB::commit();
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
            $screen   = "insertOrderPay From OrderRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            DB::rollBack();
            $screen = "insertOrderPay From OrderRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function getSettingData()
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $setting = Setting::select('id', 'company_name', 'company_phone', 'company_email', 'company_address', 'company_logo')
                    ->whereNull('deleted_at')
                    ->first();
            return $setting;
        } catch (\Exception $e) {
            $screen = "GetSettingData From OrderRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
            return $returnArray;
        }
    }
}
