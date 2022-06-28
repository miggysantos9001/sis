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
use Config;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class SetupController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        if (Request::ajax()) {
            $data = \App\Setup::latest()->get();
            return Datatables::of($data)
            ->editColumn('name', function($data){
                $bn = $data->name.'<br><small>'.$data->cname.'</small>';
                return $bn;
            })
            ->addColumn('action', function($data){
                $btn = '<center>
                    <a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
                    <a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
                </center>';
                return $btn;
            })
            ->rawColumns(['action','name'])
            ->make(true);
        }
        $setups = \App\Setup::orderBy('name')->get();
        return view('admin.setups.index',compact('setups'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'name'                      =>  'required',
            'description'               =>  'required',
            'owner'                     =>  'required',
        ],
        [
            'name.required'             =>  'System Name Required',
            'description.required'      =>  'System Description Required',
            'owner.required'            =>  'System Owner Required',
        ]);

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                toastr()->warning($error);
            }
            return redirect()->back()
            ->withInput();
        }

        $data = Request::except('pic');
        $file = Request::file('pic');

        if($file !== NULL){
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::random(40).'.'.$file->extension();
            $file->move(public_path().'/images',$fileName);
        }else{
            $fileName = NULL;
        }

        $data = Arr::add($data,'logo',$fileName);

        \App\Setup::create($data);

        toastr()->success('Setup Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update($id){
        $setup = \App\Setup::find($id);
        $validator = Validator::make(Request::all(), [
            'name'                      =>  'required',
            'description'               =>  'required',
            'owner'                     =>  'required',
        ],
        [
            'name.required'             =>  'System Name Required',
            'description.required'      =>  'System Description Required',
            'owner.required'            =>  'System Owner Required',
        ]);

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                toastr()->warning($error);
            }
            return redirect()->back()
            ->withInput();
        }

        $data = Request::except('pic');
        $file = Request::file('pic');

        if($file !== NULL){
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::random(40).'.'.$file->extension();
            $file->move(public_path().'/images',$fileName);
        }else{
            $fileName = $setup->logo;
        }

        $data = Arr::add($data,'logo',$fileName);

        $setup->update($data);

        toastr()->success('Setup Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function delete($id){
        $setup = \App\Setup::find($id);
        $setup->delete();

        toastr()->success('Setup Deleted Successfully', config('global.system_name'));
        return redirect()->back();

    }

    public function switchLang($lang)
    {
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('applocale', $lang);
        }
        return redirect()->back();
    }
}
