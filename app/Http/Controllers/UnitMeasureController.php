<?php

namespace App\Http\Controllers;

use App\Unit;
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

class UnitMeasureController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        if (Request::ajax()) {
            $data = Unit::latest()->get();
            return Datatables::of($data)
            ->addColumn('action', function($data){
                $btn = '<center>
                <span data-toggle="tooltip" data-placement="top" title="Edit Branch">
                    <a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
                </span>
                </center>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        $units = Unit::orderBy('name')->get();
        return view('admin.unit-measures.index',compact('units'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'name'                      =>  'required|unique:units',
        ],
        [
            'name.required'             =>  'Unit of Measure Name Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Unit::create([
            'name'          =>      Request::get('name'),
        ]);

        toastr()->success('Unit of Measure Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update($id){
        $unit = Unit::find($id);
        $validator = Validator::make(Request::all(), [
            'name'                      =>  "required|unique:units,name,$unit->id,id",
        ],
        [
            'name.required'             =>  'Unit of Measure Name Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $unit->update([
            'name'          =>      Request::get('name'),
        ]);

        toastr()->success('Unit of Measure Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }
}
