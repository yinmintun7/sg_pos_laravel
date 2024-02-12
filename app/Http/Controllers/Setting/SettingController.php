<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\ResponseStatus;
use App\Utility;
use App\Models\Item;
use App\Http\Requests\SettingStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Requests\ItemDeleteRequest;
use App\Repository\Setting\SettingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private $SettingRepository;
    public function __construct(SettingRepositoryInterface $SettingRepository)
    {
        DB::connection()->enableQueryLog();
        $this->SettingRepository = $SettingRepository;
    }

    public function form()
    {
        return view('backend.setting.form');

    }

    public function create(SettingStoreRequest $request)
    {
        try {
            $store = $this->SettingRepository->create((array) $request->all());
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

    // public function getItems()
    // {
    //     try {
    //         $items = $this->ItemRepository->getItems();
    //         $screen = "GetItems From ItemController::";
    //         $queryLog = DB::getQueryLog();
    //         Utility::saveDebugLog($screen, $queryLog);
    //         return view('backend.item.item_list', compact(['items']));
    //     } catch (\Exception $e) {
    //         $screen = "GetItems From ItemController::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }
    // }

    // public function itemEditForm($id)
    // {
    //     $item = Item::find($id);
    //     return view('backend.item.form', compact(['item']));
    // }

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

    // public function deleteItem(ItemDeleteRequest $request)
    // {

    //     try {
    //         $delete_item = $this->ItemRepository->deleteItem((int) $request->id);
    //         if ($delete_item['ResponseStatus'] == ResponseStatus::OK) {
    //             return redirect()->back()->with(['success' => 'Success!,item is deleted!']);
    //         } else {
    //             return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot delete item!']);
    //         }
    //         $screen = "DeleteItem From ItemController::";
    //         $queryLog = DB::getQueryLog();
    //         Utility::saveDebugLog($screen, $queryLog);
    //     } catch (\Exception $e) {
    //         $screen = "DeleteItem From ItemController::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }
    // }


}
