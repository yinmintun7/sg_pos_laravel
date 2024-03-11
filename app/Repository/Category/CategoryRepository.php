<?php

namespace App\Repository\Category;

use App\Constant;
use App\Models\Category;
use App\ResponseStatus;
use App\Utility;
use Illuminate\Support\Facades\DB;

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
            $screen = "CreateCategory From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        }
    }

    public function getCategory()
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $categories = [];
            $categories = Category::from('category as c')
            ->select('c.id', 'c.name', 'c.parent_id', 'c.status', 'c.image')
            ->leftJoin('category as p', 'c.parent_id', '=', 'p.id')
            ->whereNull('c.deleted_at')
            ->orderByDesc('c.id')
            ->addSelect(DB::raw('COALESCE(p.name, "None") as parent_name'))
            ->paginate(10);
            return $categories;
        } catch (\Exception $e) {
            $screen = "GetCategory From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        }
    }

    public function getCategoryById(int $id)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $category = Category::find($id);
            return $category;

        } catch (\Exception $e) {
            $screen = "GetCategoryById From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        }
    }

    public function getCategoryByParentId(int $id)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $categories = Category::where('parent_id', $id)
                        ->whereNull('deleted_at')
                        ->where('status', Constant::ENABLE_STATUS)
                        ->get();
            return $categories;
        } catch (\Exception $e) {
            $screen = "getCategoryByParentId From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        }
    }

    public function updateCategory($request)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $id = $request['id'];
            $name = $request['name'];
            $parent_id = $request['parent_id'];
            $status = $request['status'];
            $update_data = [];
            $update = Category::find($id);
            if (array_key_exists('image', $request)) {
                $name_without_extension = pathinfo($request['image']->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $request['image']->getClientOriginalExtension();
                $unique_name = $name_without_extension . '-' . now()->format('Y-m-d_His') . '-' . uniqid() . '.' . $extension;
                $destination_path = storage_path('/app/public/upload/category/' . $id);
                Utility::cropResize($request['image'], $destination_path, $unique_name);
                $update_data['image'] = $unique_name;
                $old_image     = storage_path('/app/public/upload/category/' . $id.'/'.$update->image);
                unlink($old_image);
            }
            $update_data['name']      = $name;
            $update_data['parent_id'] = $parent_id;
            $update_data['status']    = $status;
            $confirm_update = Utility::getUpdateId((array)$update_data);
            $update->update($confirm_update);
            $screen   = "UpdateCategory From Category Form Screen::";
            $queryLog = DB::getQueryLog();
        } catch (\Exception $e) {
            $screen = "UpdateCategory From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        }
    }

    public function deleteCategory($id)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $delete_data = [];
            $delete = Category::find($id);
            $confirm_delete = Utility::getDeletedId((array)$delete_data);
            $delete->update($confirm_delete);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "DeleteCategory From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        }
    }
}
