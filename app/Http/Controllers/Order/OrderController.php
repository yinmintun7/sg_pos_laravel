<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Api\GetCategoryRequest;
use App\Http\Requests\Api\GetItemRequest;
use App\Http\Requests\Api\GetItemByCatRequest;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Requests\Api\GetOrderListRequest;
use App\Http\Requests\Api\OrderStatusRequest;
use App\Repository\Order\OrderRepositoryInterface;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Item\OrderItemResource;
use App\Http\Resources\Item\ItemResource;
use App\Http\Resources\Item\GetOrderListResource;
use App\Models\Item;
use App\Repository\Category\CategoryRepositoryInterface;
use App\Repository\Item\ItemRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\ResponseStatus;

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
    public function getAllItem(Request $request)
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
            // $category = $this->OrderRepository->getEditOrder((int) $id);
            // if ($category == null) {
            //     return response()->view('errors.404', [], 404);
            // }
            // $screen   = "GetCategoryById From CategoryController::";
            // $queryLog = DB::getQueryLog();
            // Utility::saveDebugLog($screen, $queryLog);
            return view('frontend.order.order', compact(['id']));
        } catch (\Exception $e) {
            $screen = "GetCategoryById From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function getOrderItems(Request $request)
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

    public function getOrderDetail(Request $request)
    {
        dd($request->all());
        try {
            $items = $this->OrderRepository->getOrderDetail((array)$request->all());
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
}
