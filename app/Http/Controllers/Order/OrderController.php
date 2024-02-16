<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repository\Order\OrderRepositoryInterface;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Item\ItemResource;
use App\Repository\Category\CategoryRepositoryInterface;
use App\Repository\Item\ItemRepositoryInterface;

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
    public function getCategory(Request $request)
    {
        $categories = $this->CategoryRepository->getCategoryByParentId((int) $request->parent_id);
        return CategoryResource::collection($categories);

    }
    public function getAllItem(Request $request)
    {
        $items = $this->ItemRepository->getItems((bool) true);
        return ItemResource::collection($items);
    }
    public function getItemByCategory(Request $request)
    {
        $items = $this->ItemRepository->getItemByCategory((int) $request->category_id, (bool) true);
        return ItemResource::collection($items);
    }
    public function getItemData(Request $request)
    {
        $items = $this->ItemRepository->getItemData((int) $request->item_id);
        return ItemResource::collection($items);
    }
}
