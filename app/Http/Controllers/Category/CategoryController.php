<?php

namespace App\Http\Controllers\Category;

use App\ResponseStatus;
use App\Utility;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Repository\Category\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    private $CategoryRepository;
    public function __construct(CategoryRepositoryInterface $CategoryRepository)
    {
        DB::connection()->enableQueryLog();
        $this->CategoryRepository = $CategoryRepository;
    }

    public function form(){

        return view('backend.category.form');

    }

    public function create(CategoryStoreRequest $request){
        try{
           $store = $this->CategoryRepository->create((array) $request->all());
           if($store['ResponseStatus'] == ResponseStatus::OK){
            return redirect('/sg-backend/category/list')->with('category_create', 'Success! Category created!');
           }else{
            return redirect()->back()->withErrors(['category_error' => 'Fail!,Cannot create category!']);
           }
           $screen = "Category create From Category Form Screen::";
           $queryLog = DB::getQueryLog();
           Utility::saveDebugLog($screen, $queryLog);

        }catch(\Exception $e){
            $screen = "Category create From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function getCategory(){
        try{
            $categories = $this->CategoryRepository->getCategory();
            $screen = "GetCategory From Category Form Screen::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.category.category_list', compact(['categories']));
        }catch(\Exception $e){
            $screen = "GetCategory From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function categoryEditForm($id)
    {
        $category = Category::find($id);
        return view('backend.category.form', compact(['category']));
    }

    public function updateCategory(CategoryUpdateRequest $request){
        try{
            dd($request->all());
            $update_cat = $this->CategoryRepository->updateCategory($request->all());
            dd($update_cat);
            $screen = "UpdateCategory From Category Form Screen::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        }catch(\Exception $e){
            $screen = "UpdateCategory From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function deleteCategory($id){
        try{
            $delete_cat = $this->CategoryRepository->deleteCategory($id);
            if($delete_cat['ResponseStatus'] == ResponseStatus::OK){
                return redirect()->back()->with(['category_delete' => 'Success!,category is deleted!']);
            }else{
                return redirect()->back()->withErrors(['category_error' => 'Fail!,Cannot delete category!']);
            }
            $screen = "DeleteCategory From Category Form Screen::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        }catch(\Exception $e){
            $screen = "DeleteCategory From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

}
