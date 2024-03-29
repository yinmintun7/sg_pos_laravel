<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemDeleteRequest;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\Item;
use App\Repository\Item\ItemRepositoryInterface;
use App\ResponseStatus;
use App\Utility;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    private $ItemRepository;
    public function __construct(ItemRepositoryInterface $ItemRepository)
    {
        DB::connection()->enableQueryLog();
        $this->ItemRepository = $ItemRepository;
    }

    public function form()
    {
        try {
            return view('backend.item.form');
        } catch (\Exception $e) {
            $screen = "ItemCreateFrom From ItemController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function create(ItemStoreRequest $request)
    {
        try {
            $validatedData = $request->validated();
            if (!$validatedData) {
                return redirect()->back()->withErrors($request->errors())->withInput();
            }
            $store = $this->ItemRepository->create((array) $request->all());
            if ($store['ResponseStatus'] == ResponseStatus::OK) {
                return redirect('/sg-backend/item/list')->with('success', 'Success! Item created!');
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot create Item!']);
            }
            $screen = "ItemCreate From ItemController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "ItemCreate From ItemController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function getItems()
    {
        try {
            $items = $this->ItemRepository->getItems();
            $screen = "GetItems From ItemController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.item.item_list', compact(['items']));
        } catch (\Exception $e) {
            $screen = "GetItems From ItemController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function itemEditForm($id)
    {
        try {
            $item = Item::find($id);
            return view('backend.item.form', compact(['item']));
        } catch (\Exception $e) {
            $screen = "ItemCreateFrom From ItemController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function updateItem(ItemUpdateRequest $request)
    {
        try {
            $update_cat = $this->ItemRepository->updateItem($request->all());
            if ($update_cat['ResponseStatus'] == ResponseStatus::OK) {
                return redirect('/sg-backend/item/list')->with('success', 'Success! Item Updated!');
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot create category!']);
            }
            $screen = "UpdateItem From ItemController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "UpdateItem From ItemController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function deleteItem(ItemDeleteRequest $request)
    {
        try {
            $delete_item = $this->ItemRepository->deleteItem((int) $request->id);
            if ($delete_item['ResponseStatus'] == ResponseStatus::OK) {
                return redirect()->back()->with(['success' => 'Success!,item is deleted!']);
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot delete item!']);
            }
            $screen = "DeleteItem From ItemController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "DeleteItem From ItemController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
