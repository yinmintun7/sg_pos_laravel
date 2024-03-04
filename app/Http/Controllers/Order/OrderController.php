<?php

namespace App\Http\Controllers\Order;

use App\Utility;
use App\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderListReport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetItemRequest;
use App\Http\Resources\Item\ItemResource;
use App\Http\Resources\Order\OrderResource;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Requests\Api\GetCategoryRequest;
use App\Http\Requests\Api\OrderStatusRequest;
use App\Http\Requests\Api\GetItemByCatRequest;
use App\Http\Requests\Api\GetOrderListRequest;
use App\Http\Resources\Item\OrderItemResource;
use App\Http\Requests\Api\getOrderItemsRequest;
use App\Http\Resources\Setting\SettingResource;
use App\Repository\Item\ItemRepositoryInterface;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Item\GetOrderListResource;
use App\Repository\Order\OrderRepositoryInterface;
use App\Repository\Category\CategoryRepositoryInterface;

class OrderController extends Controller
{
    private $OrderRepository;
    private $CategoryRepository;
    private $ItemRepository;
    public function __construct(
        OrderRepositoryInterface $OrderRepository,
        CategoryRepositoryInterface $CategoryRepository,
        ItemRepositoryInterface $ItemRepository
    ) {
        DB::connection()->enableQueryLog();
        $this->OrderRepository = $OrderRepository;
        $this->CategoryRepository = $CategoryRepository;
        $this->ItemRepository = $ItemRepository;
    }

    public function orderList()
    {
        return view('frontend.order.list');
    }
    public function order()
    {
        return view('frontend.order.order');
    }
    public function getCategory(GetCategoryRequest $request)
    {
        try {
            $categories = $this->CategoryRepository->getCategoryByParentId((int) $request->parent_id);
            $screen = "loadParentCategory From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return CategoryResource::collection($categories);
        } catch (\Exception $e) {
            $screen = "loadParentCategory From OrderController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }
    public function getAllItem()
    {
        try {
            $items = $this->ItemRepository->getItems((bool) true);
            $screen = "loadAllItems From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return ItemResource::collection($items);
        } catch (\Exception $e) {
            $screen = "loadAllItems From OrderController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function getItemByCategory(GetItemByCatRequest $request)
    {
        try {
            $items = $this->ItemRepository->getItemByCategory((int) $request->category_id, (bool) true);
            return ItemResource::collection($items);
            $screen = "loadGetItemByCategory From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "loadGetItemByCategory From OrderController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }
    public function getItemData(GetItemRequest $request)
    {
        try {
            $item = $this->OrderRepository->getOrderItemById((int) $request->item_id);
            return new OrderItemResource($item);
            $screen = "loadgetItemData From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "loadgetItemData From OrderController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function storeOrder(StoreOrderRequest $request)
    {
        try {
            $this->OrderRepository->storeOrderItems((array) $request->all());
            $screen = "storeOrderItem From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return new JsonResponse([
                'success' => true,
                'message' => 'success store order',
                'status'  => ResponseStatus::OK,
            ]);
        } catch (\Exception $e) {
            $screen = "storeOrderItem From OrderController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function getOrderList(GetOrderListRequest $request)
    {
        try {
            $order_list = $this->OrderRepository->getOrderList((int) $request->shift_id);
            $screen = "loadOrderList From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return GetOrderListResource::collection($order_list);
        } catch (\Exception $e) {
            $screen = "loadOrderList From OrderController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function orderListPage(int $id)
    {
        try {
            $order_list = $this->OrderRepository->getOrderList((int) $id);
            $screen = "loadOrderList From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.shift.order_list', compact(['order_list','id']));
        } catch (\Exception $e) {
            $screen = "loadOrderList From OrderController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function downloadOrderListExcel(OrderListReport $orderListReport, $id)
    {
        try {
            $name    = date('Ymdhis').'_'.'order_list.xlsx';
            return Excel::download($orderListReport->setShiftId($id), $name);
            $screen = "Download Orderlist From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "Download Orderlist From OrderController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function CancelOrder(OrderStatusRequest $request)
    {
        try {
            $cancel_order = $this->OrderRepository->CancelOrder((array) $request->all());
            $screen = "cancelOrder From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return new JsonResponse([
                'success' => true,
                'message' => 'success cancel order',
                'status'  => ResponseStatus::OK,
            ]);
        } catch (\Exception $e) {
            $screen = "cancelOrder From OrderController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function EditOrder(int $id)
    {
        try {

            return view('frontend.order.order', compact(['id']));
        } catch (\Exception $e) {
            $screen = "GetCategoryById From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function getOrderItems(getOrderItemsRequest $request)
    {
        try {
            $items = $this->OrderRepository->getOrderItems((int) $request->id);
            return OrderItemResource::collection($items);
            $screen = "cancelOrder From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "GetCategoryById From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function updateOrder(Request $request)
    {
        try {
            $items = $this->OrderRepository->updateOrder((array) $request->all());
            return new JsonResponse([
                'success' => true,
                'message' => 'success cancel order',
                'status'  => ResponseStatus::OK,
            ]);
            $screen = "updateOrder From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "updateOrder From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function getPaymentPage(int $id)
    {
        return view('frontend.order.payment', compact(['id']));
    }
    public function getOrderDetailPage(int $id)
    {
        return view('frontend.order.order_detail', compact(['id']));
    }

    public function getOrderDetail(Request $request)
    {
        try {
            $order = $this->OrderRepository->getOrderDetail((array)$request->all());
            return new OrderResource($order);
            $screen = "updateOrder From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "updateOrder From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function insertPayOrder(Request $request)
    {
        try {
            $order = $this->OrderRepository->insertPayOrder((array)$request->all());
            return new JsonResponse([
                'success' => true,
                'message' => 'success cancel order',
                'status'  => ResponseStatus::OK,
            ]);
            $screen = "insertOrderPay From OrderRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "insertOrderPay From OrderRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function getSettingData()
    {
        try {
            $setting = $this->OrderRepository->getSettingData();
            return new SettingResource($setting);
            $screen = "getSettingData From OrderController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "getSettingData From OrderController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
