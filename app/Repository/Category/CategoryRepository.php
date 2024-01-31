<?php

namespace App\Repository\Category;

use App\Constant;
use App\Models\Category;
use App\Utility;
use App\ResponseStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function create(array $data)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $insert_data = [];
            $insert_data['name']      = $data['name'];
            $insert_data['parent_id'] = $data['parent_id'];
            $insert_data['status']    = $data['status'];
            $file = $data['image'];
            $name_without_extension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $unique_name = $name_without_extension . '-' . now()->format('Y-m-d_His') . '-' . uniqid() . '.' . $extension;
            $insert_data['image'] = $unique_name;
            $store = Utility::getCreateId((array)$insert_data);
            $create_cat = Category::create($store);
            $destination_path = storage_path('/app/public/upload/category/' . $create_cat->id);
            Utility::cropResize($file, $destination_path, $unique_name);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            dd($e->getMessage());
            abort(500);
        }
    }

    public function getCategory()
    {
        try {
            $categories = [];
            $categories= Category::from('category as c')
            ->select('c.id', 'c.name', 'c.parent_id', 'c.status', 'c.image')
            ->leftJoin('category as p', 'c.parent_id', '=', 'p.id')
            ->whereNull('c.deleted_at')
            ->orderByDesc('c.id')
            ->addSelect(DB::raw('COALESCE(p.name, "None") as parent_name'))
            ->get();
            return $categories;
        } catch (\Exception $e) {
            $screen = "GetCategory From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function updateCategory($category)
    {
        try {
            // $id = $request->id;
            // $setting = Setting::find($id);
            // $update = $request->all();
            // $setting->updated_at = date('Y-m-d H:i:s');
            // $setting->update($update);
        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function  deleteCategory($id)
    {
        $user_id    = Auth::guard('admin')->user()->id;
        try {
            $delete = Category::find($id);
            $delete->deleted_at = date('Y-m-d H:i:s');
            $delete->updated_by = $user_id;
            $delete->deleted_by = $user_id;
            $delete->save();
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            abort(500);
        }
    }
}
