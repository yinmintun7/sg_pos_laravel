<?php

namespace App\Repository\User;

use App\Constant;
use App\Models\User;
use App\Utility;
use App\ResponseStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data)
    {
        $returnArray  = array();
        $returnArray['ResponseStatus'] = ResponseStatus::INTERNAL_SERVER_ERROR;
        try {
            $insert_data = [];
            $insert_data['username'] = $data['username'];
            $insert_data['password'] = bcrypt($data['password']);
            $insert_data['status']   = $data['status'];
            if ($data['usertype'] == 'admin') {
                $insert_data['role'] = Constant::ADMIN_ROLE;
            }
            if ($data['usertype'] == 'cashier') {
                $insert_data['role'] = Constant::CASHIER_ROLE;
            }
            $store = Utility::getCreateId((array)$insert_data);
            User::create($store);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            dd($e->getMessage());
            abort(500);
        }
    }
    public function getUsers()
    {
        try {
            $users = [];
            $users = User::select('id', 'username', 'password', 'status', DB::raw("IF(role = " . Constant::ADMIN_ROLE . ", 'Admin', IF(role = " . Constant::CASHIER_ROLE . ", 'Cashier', 'Other')) AS role_name"))
            ->whereNull('deleted_at')
            ->paginate(10);
            return $users;
        } catch (\Exception $e) {
            $screen = "GetUsers From UserRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function getUserById(int $id)
    {
        try {
            $user = User::find($id);
            return $user;
            $screen   = "GetUserById From UserRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "GetUserById From UserRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
    public function update($request)
    {

        try {
            $id = $request['id'];
            $update_data = [];
            $update_data['username'] = $request['username'];
            $update_data['password'] = bcrypt($request['password']);
            $update_data['status']   = $request['status'];
            if ($request['usertype'] == 'admin') {
                $update_data['role'] = Constant::ADMIN_ROLE;
            }
            if ($request['usertype'] == 'cashier') {
                $update_data['role'] = Constant::CASHIER_ROLE;
            }
            $update = User::find($id);
            $confirm_update = Utility::getUpdateId((array)$update_data);
            $update->update($confirm_update);
            $screen   = "UpdateUser From UserRepository::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "UpdateUser From UserRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function delete($id)
    {
        try {
            $delete_data = [];
            $delete = User::find($id);
            $confirm_delete = Utility::getDeletedId((array)$delete_data);
            $delete->update($confirm_delete);
            $returnArray['ResponseStatus'] = ResponseStatus::OK;
            return $returnArray;
        } catch (\Exception $e) {
            $screen = "DeleteCategory From CategoryRepository::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
