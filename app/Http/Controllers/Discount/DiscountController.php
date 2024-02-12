<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\Controller;
use App\ResponseStatus;
use App\Utility;
use App\Http\Requests\DiscountStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Requests\DiscountDeleteRequest;
use App\Repository\Discount\DiscountRepositoryInterface;
use App\Repository\Item\ItemRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    private $DiscountRepository;
    private $ItemRepository;
    public function __construct(DiscountRepositoryInterface $DiscountRepository, ItemRepositoryInterface $ItemRepository)
    {
        DB::connection()->enableQueryLog();
        $this->DiscountRepository = $DiscountRepository;
        $this->ItemRepository = $ItemRepository;
    }

    public function form()
    {
        try {
            $screen = "DiscountForm From DiscountController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            $items = $this->ItemRepository->getItems();
            return view('backend.discount.form', compact(['items']));
        } catch (\Exception $e) {
            $screen = "DiscountForm From DiscountController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function create(DiscountStoreRequest $request)
    {
        try {
            $store = $this->DiscountRepository->create((array) $request->all());
            if ($store['ResponseStatus'] == ResponseStatus::OK) {
                return redirect('/sg-backend/discount/list')->with('success', 'Success! Item created!');
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot create Item!']);
            }
            $screen = "DiscountCreate from DiscountController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);

        } catch (\Exception $e) {
            $screen = "DiscountCreate from DiscountController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function getDiscount()
    {
        try {
            $discounts = $this->DiscountRepository->getDiscount();
            // dd($discount_items);
            $screen = "GetItems From ItemController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.discount.discount_list', compact(['discounts']));
            $screen = "GetDiscount from DiscountController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "GetDiscount from DiscountController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }


    public function edit(int $id)
    {
        try {
            $discount = $this->DiscountRepository->getDiscountById((int) $id);
            if ($discount == null) {
                return response()->view('errors.404', [], 404);
            }
            //dd($discount);
            $items = $this->ItemRepository->getItems();
            $screen   = "GetCategoryById From CategoryController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.discount.form', compact(['discount','items']));
        } catch (\Exception $e) {
            $screen = "GetCategoryById From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    // public function updateItem(ItemUpdateRequest $request)
    // {

    //     try {
    //         $update_cat = $this->ItemRepository->updateItem($request->all());
    //         if ($update_cat['ResponseStatus'] == ResponseStatus::OK) {
    //             return redirect('/sg-backend/item/list')->with('success', 'Success! Category created!');
    //         } else {
    //             return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot create category!']);
    //         }
    //         $screen = "UpdateItem From ItemController::";
    //         $queryLog = DB::getQueryLog();
    //         Utility::saveDebugLog($screen, $queryLog);
    //     } catch (\Exception $e) {
    //         $screen = "UpdateItem From ItemController::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }

    // }

    public function delete(DiscountDeleteRequest $request)
    {

        try {
            $delete_item = $this->DiscountRepository->delete((int) $request->id);
            if ($delete_item['ResponseStatus'] == ResponseStatus::OK) {
                return redirect()->back()->with(['success' => 'Success!,DiscountPromotion is deleted!']);
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot delete DiscountPromotion!']);
            }
            $screen = "DeleteDiscountPromotion From ItemController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "DeleteDiscountPromotion From ItemController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }


}
