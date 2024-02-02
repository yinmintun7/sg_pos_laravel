<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\ResponseStatus;
use App\Utility;
use App\Models\Item;
use App\Http\Requests\ItemStoreRequest;
use App\Repository\Item\ItemRepositoryInterface;
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

        return view('backend.item.form');

    }

    public function create(ItemStoreRequest $request)
    {

        try {
            $store = $this->ItemRepository->create((array) $request->all());
            if ($store['ResponseStatus'] == ResponseStatus::OK) {
                // return redirect('/sg-backend/item/list')->with('success', 'Success! Item created!');
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

}
