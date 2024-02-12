<?php

namespace App\Http\Controllers\User;

use App\Utility;
use App\ResponseStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repository\User\UserRepositoryInterface;

class UserController extends Controller
{
    private $UserRepository;
    public function __construct(UserRepositoryInterface $UserRepository)
    {
        DB::connection()->enableQueryLog();
        $this->UserRepository = $UserRepository;
    }

    public function form()
    {

        return view('backend.user.form');

    }

    public function create(UserStoreRequest $request)
    {
        try {
            $store = $this->UserRepository->create((array) $request->all());
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
    public function list()
    {
        try {
            $users = $this->UserRepository->getUsers();
            $screen = "GetUser From UserController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.user.user_list', compact(['users']));
        } catch (\Exception $e) {
            $screen = "GetUser From UserController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function edit(int $id)
    {
        try {
            $user = $this->UserRepository->getUserById((int) $id);
            if ($user == null) {
                return response()->view('errors.404', [], 404);
            }
            $screen   = "UserEdit From UserController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.user.form', compact(['user']));
        } catch (\Exception $e) {
            $screen = "UserEdit From UserController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    // public function updateCategory(CategoryUpdateRequest $request)
    // {
    //     try {
    //         $update_cat = $this->UserRepository->update($request->all());
    //         if ($update_cat['ResponseStatus'] == ResponseStatus::OK) {
    //             return redirect('/sg-backend/category/list')->with('success', 'Success! Category created!');
    //         } else {
    //             return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot create category!']);
    //         }
    //         $screen = "UpdateCategory From Category Form Screen::";
    //         $queryLog = DB::getQueryLog();
    //         Utility::saveDebugLog($screen, $queryLog);
    //     } catch (\Exception $e) {
    //         $screen = "UpdateCategory From Category Form Screen::";
    //         Utility::saveErrorLog($screen, $e->getMessage());
    //         abort(500);
    //     }

    // }

    public function delete(UserDeleteRequest $request)
    {
        try {
            $delete_user = $this->UserRepository->delete((int) $request->id);
            if ($delete_user['ResponseStatus'] == ResponseStatus::OK) {
                return redirect()->back()->with(['success' => 'Success!,category is deleted!']);
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot delete category!']);
            }
            $screen = "DeleteUser From CategoryController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "DeleteUser From  CategoryController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
