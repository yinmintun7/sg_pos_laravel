<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ResponseStatus;
use App\Utility;
use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Repository\Category\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    private $CategoryRepository;
    public function __construct(CategoryRepositoryInterface $CategoryRepository)
    {
        DB::connection()->enableQueryLog();
        $this->CategoryRepository = $CategoryRepository;
    }

    public function form(){

        return view('backend.item.form');

    }

    public function create(CategoryStoreRequest $request){
        try{
           $store = $this->CategoryRepository->create((array) $request->all());
           if($store['ResponseStatus'] == ResponseStatus::OK){
            return redirect('/sg-backend/category/list')->with('success', 'Success! Category created!');
           }else{
            return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot create category!']);
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
            $update_cat = $this->CategoryRepository->updateCategory($request->all());
            if($update_cat['ResponseStatus'] == ResponseStatus::OK){
                return redirect('/sg-backend/category/list')->with('success', 'Success! Category created!');
               }else{
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot create category!']);
               }
            $screen = "UpdateCategory From Category Form Screen::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        }catch(\Exception $e){
            $screen = "UpdateCategory From Category Form Screen::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function deleteCategory($request){
        dd($request->all());

        try{
            // $delete_cat = $this->CategoryRepository->deleteCategory($id);
            // if($delete_cat['ResponseStatus'] == ResponseStatus::OK){
            //     return redirect()->back()->with(['success' => 'Success!,category is deleted!']);
            // }else{
            //     return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot delete category!']);
            // }
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
