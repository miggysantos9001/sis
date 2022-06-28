<?php

namespace App\Http\Controllers;

use Excel;
use Validator;
use Auth;
use PDF;
use DB;
use Session;
use Input;
use Request;
use DateTime;
use Hash;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class SettingController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        if (Request::ajax()) {
            $data = \App\Systemsetting::latest()->get();
            return Datatables::of($data)
            ->editColumn('branch_id', function($data){
                $bn = $data->branch->name;
                return $bn;
            })
            ->editColumn('name', function($data){
                $cn = $data->name;
                return $cn;
            })
            ->addColumn('action', function($data){
                $btn = '<center>
                <span data-toggle="tooltip" data-placement="top" title="Edit Setting">
                <a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
                </span>
                </center>';

                return $btn;
            })
            ->rawColumns(['action','name'])
            ->make(true);
        }
        $settings = \App\Systemsetting::orderBy('name')->get();
        $branches = \App\Branch::pluck('name','id');
        return view('admin.settings.index',compact('settings','branches'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'name'          =>      'required',
            'address'       =>      'required',
            'contact'       =>      'required',
            'branch_id'     =>      'required',
        ],
        [
            'name.required'         =>  'Name Required',
            'address.required'      =>  'Address Required',
            'contact.required'      =>  'Contact Number Required',
            'branch_id.required'    =>  'Please select Branch',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        \App\Systemsetting::create(Request::all());

        toastr()->success('Settings Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update($id){
        $setting = \App\Systemsetting::find($id);
        $validator = Validator::make(Request::all(), [
            'name'          =>      'required',
            'address'       =>      'required',
            'contact'       =>      'required',
            'branch_id'     =>      'required',
        ],
        [
            'name.required'         =>  'Name Required',
            'address.required'      =>  'Address Required',
            'contact.required'      =>  'Contact Number Required',
            'branch_id.required'    =>  'Please select Branch',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $setting->update(Request::all());

        toastr()->success('Settings Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }
}
