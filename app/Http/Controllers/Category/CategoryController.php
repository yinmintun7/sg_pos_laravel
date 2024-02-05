<?php

namespace App\Http\Controllers\Category;

use App\Utility;
use App\ResponseStatus;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryDeleteRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Repository\Category\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    private $CategoryRepository;
    public function __construct(CategoryRepositoryInterface $CategoryRepository)
    {
        DB::connection()->enableQueryLog();
        $this->CategoryRepository = $CategoryRepository;
    }

    public function form()
    {

        return view('backend.category.form');

    }

    public function create(CategoryStoreRequest $request)
    {
        try {
            $store = $this->CategoryRepository->create((array) $request->all());
            if ($store['ResponseStatus'] == ResponseStatus::OK) {
                return redirect('/sg-backend/category/list')->with('success', 'Success! Category created!');
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot create category!']);
            }
            $screen = "Category create From Category Form Screen::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);

        } catch (\Exception $e) {
            $screen = "Category create From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function getCategory()
    {
        try {
            $categories = $this->CategoryRepository->getCategory();
            $screen = "GetCategory From Category Form Screen::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.category.category_list', compact(['categories']));
        } catch (\Exception $e) {
            $screen = "GetCategory From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function editCategory(int $id)
    {
        try{
            $category =$this->CategoryRepository->getCategoryById((int) $id);
            if($category == null){
               return response()->view('errors.404',[],404);
            }
            $screen   = "GetCategoryById From CategoryController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.category.form', compact(['category']));
        }catch(\Exception $e){
            $screen = "GetCategoryById From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function updateCategory(CategoryUpdateRequest $request)
    {
        try {
            $update_cat = $this->CategoryRepository->updateCategory($request->all());
            if ($update_cat['ResponseStatus'] == ResponseStatus::OK) {
                return redirect('/sg-backend/category/list')->with('success', 'Success! Category created!');
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot create category!']);
            }
            $screen = "UpdateCategory From Category Form Screen::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "UpdateCategory From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function deleteCategory(CategoryDeleteRequest $request)

    {
        try {
            $delete_cat = $this->CategoryRepository->deleteCategory((int) $request->id);
            if ($delete_cat['ResponseStatus'] == ResponseStatus::OK) {
                return redirect()->back()->with(['success' => 'Success!,category is deleted!']);
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot delete category!']);
            }
            $screen = "DeleteCategory From CategoryController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "DeleteCategory From  CategoryController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

}
