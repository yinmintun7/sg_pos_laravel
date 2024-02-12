<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\ResponseStatus;
use App\Utility;
use App\Http\Requests\Setting\SettingStoreRequest;
use App\Http\Requests\Setting\SettingUpdateRequest;
use App\Http\Requests\Setting\SettingDeleteRequest;
use App\Repository\Setting\SettingRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        try {
            $setting = $this->SettingRepository->getSetting();
            return view('backend.setting.form', compact(['setting']));
        } catch (\Exception $e) {
            $screen = "SettingForm From SettingController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }


    }

    public function create(SettingStoreRequest $request)
    {
        try {
            $store = $this->SettingRepository->create((array) $request->all());
            if ($store['ResponseStatus'] == ResponseStatus::OK) {
                return redirect('/sg-backend/setting/list')->with('success', 'Success! Setting created!');
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot create Setting!']);
            }
            $screen = "SettingCreate From SettingController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);

        } catch (\Exception $e) {
            $screen = "SettingCreate From SettingController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function edit()
    {
        try {
            $setting = $this->SettingRepository->getSetting();
            if ($setting == null) {
                return response()->view('errors.404', [], 404);
            }
            $screen   = "EditSetting From SettingController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.setting.form', compact(['setting']));
        } catch (\Exception $e) {
            $screen = "EditSetting From SettingController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function list()
    {
        try {
            $setting = $this->SettingRepository->getSetting();
            $screen = "SettingList From SettingController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
            return view('backend.setting.setting_list', compact(['setting']));
        } catch (\Exception $e) {
            $screen = "SettingList From SettingController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }

    public function update(SettingUpdateRequest $request)
    {
        try {
            $update_cat = $this->SettingRepository->update($request->all());
            if ($update_cat['ResponseStatus'] == ResponseStatus::OK) {
                return redirect('/sg-backend/setting/list')->with('success', 'Success! Setting updated!');
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot update Setting!']);
            }
            $screen = "UpdateSetting From SettingController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "UpdateSetting From SettingController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }

    }

    public function delete(SettingDeleteRequest $request)
    {
        try {
            $delete_item =  $this->SettingRepository->delete((int) $request->id);
            if ($delete_item['ResponseStatus'] == ResponseStatus::OK) {
                return redirect()->back()->with(['success' => 'Success!,Setting is deleted!']);
            } else {
                return redirect()->back()->withErrors(['fail' => 'Fail!,Cannot delete Setting!']);
            }
            $screen = "DeleteSetting From SettingController::";
            $queryLog = DB::getQueryLog();
            Utility::saveDebugLog($screen, $queryLog);
        } catch (\Exception $e) {
            $screen = "DeleteSetting From SettingController::";
            Utility::saveErrorLog($screen, $e->getMessage());
            abort(500);
        }
    }
}
