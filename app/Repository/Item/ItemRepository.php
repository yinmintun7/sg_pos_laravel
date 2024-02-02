<?php

namespace App\Repository\Item;

use App\Constant;
use App\Models\Item;
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

    // public function getCategory()
    // {
    //     try {
    //         $categories = [];
    //         $categories = Category::from('category as c')
    //         ->select('c.id', 'c.name', 'c.parent_id', 'c.status', 'c.image')
    //         ->leftJoin('category as p', 'c.parent_id', '=', 'p.id')
    //         ->whereNull('c.deleted_at')
    //         ->orderByDesc('c.id')
    //         ->addSelect(DB::raw('COALESCE(p.name, "None") as parent_name'))
    //         ->get();
    //         return $categories;
    //     } catch (\Exception $e) {
    //         $screen = "GetCategory From Category Form Screen::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }
    // }

    // public function updateCategory($request)
    // {
    //     try {
    //         $id = $request['id'];
    //         $name = $request['name'];
    //         $parent_id = $request['parent_id'];
    //         $status = $request['status'];
    //         $update_data = [];
    //         $update = Category::find($id);
    //         if (array_key_exists('image', $request)) {
    //             $name_without_extension = pathinfo($request['image']->getClientOriginalName(), PATHINFO_FILENAME);
    //             $extension = $request['image']->getClientOriginalExtension();
    //             $unique_name = $name_without_extension . '-' . now()->format('Y-m-d_His') . '-' . uniqid() . '.' . $extension;
    //             $destination_path = storage_path('/app/public/upload/category/' . $id);
    //             Utility::cropResize($request['image'], $destination_path, $unique_name);
    //             $update_data['image'] = $unique_name;
    //             $old_image     = storage_path('/app/public/upload/category/' . $id.'/'.$update->image);
    //             unlink($old_image);

    //         }
    //         $update_data['name']      = $name;
    //         $update_data['parent_id'] = $parent_id;
    //         $update_data['status']    = $status;
    //         $confirm_update = Utility::getUpdateId((array)$update_data);
    //         $update->update($confirm_update);
    //         $screen   = "UpdateCategory From Category Form Screen::";
    //         $queryLog = DB::getQueryLog();
    //         Utility::saveDebugLog($screen, $queryLog);
    //         $returnArray['ResponseStatus'] = ResponseStatus::OK;
    //         return $returnArray;

    //     } catch (\Exception $e) {
    //         $screen = "UpdateCategory From CategoryRepository::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }
    // }

    // public function deleteCategory($id)
    // {
    //     try {
    //         $delete_data = [];
    //         $delete = Category::find($id);
    //         $confirm_delete = Utility::getDeletedId((array)$delete_data);
    //         $delete->update($confirm_delete);
    //         $returnArray['ResponseStatus'] = ResponseStatus::OK;
    //         return $returnArray;
    //     } catch (\Exception $e) {
    //         $screen = "DeleteCategory From CategoryRepository::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }
    // }
}
