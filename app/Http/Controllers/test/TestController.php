<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequestStoreForm;
use App\Http\Requests\UpdateFormRequest;

class TestController extends Controller
{
    public function index()
    {
        // $insert_data=[
        //     'id'              =>6,
        //     'start_date_time' =>'2024-01-18 10:46:42',
        //     'end_date_time'   =>'2024-01-20 10:46:42',
        //     'status'          =>0,
        //     'created_by'      =>1,
        //     'updated_by'       =>1
        // ];
        // $id=3;
        // $update =Shift::find($id);
        // $update->start_date_time='2024-01-18 11:46:42';

        // $delete_id=2;
        // $delete =Shift::find($delete_id);
        // $delete->delete();
        // $update->updated_at=date('Y-m-d H:i:s');
        // $update->save();
        // // Shift::create($insert_data);
        // $shift =Shift::SELECT("id","start_date_time","end_date_time","created_at","created_by","updated_at")
        //        ->get();

        // return view('test.test',compact(['shift']));

    }
    public function showForm()
    {
        return view('test.show_form');
    }

    public function stroeForm(TestRequestStoreForm $request)
    {
        $data = $request->all();
        $data['updated_by'] = 1;
        $data['created_by'] = 1;
        $setting = Setting::create($data);
        return view('test.show_form', compact(['setting']));
    }

    public function showList()
    {
        $setting = Setting::SELECT("id", "company_name", "company_phone", "company_email", "company_address")
            ->whereNull('deleted_at')
            ->get();
        return view('test.list', compact(['setting']));
    }
    public function editForm($id)
    {
        $setting = Setting::find($id);
        return view('test.show_form', compact(['setting']));
    }

    public function updateForm(UpdateFormRequest $request)
    {
        $id = $request->id;
        $setting = Setting::find($id);
        $update = $request->all();
        $setting->updated_at = date('Y-m-d H:i:s');
        $setting->update($update);
    }
    public function deleteForm($id)
    {
        $delete = Setting::find($id);
        $delete->deleted_at = date('Y-m-d H:i:s');
        $delete->updated_by = 2;
        $delete->deleted_by = 1;
        $delete->save();
    }
}
