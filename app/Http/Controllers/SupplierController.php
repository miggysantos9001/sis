<?php

namespace App\Http\Controllers;

use App\Supplier;

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

class SupplierController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        if (Request::ajax()) {
            $data = Supplier::latest()->get();
            return Datatables::of($data)
            ->editColumn('name', function($data){
                $bn = $data->name;
                return $bn;
            })
            ->addColumn('action', function($data){
                $btn = '<center>
                <span data-toggle="tooltip" data-placement="top" title="Edit Supplier">
                    <a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
                </span>
                </center>';

                return $btn;
            })
            ->rawColumns(['action','name'])
            ->make(true);
        }
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.suppliers.index',compact('suppliers'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'name'                      =>  'required|unique:suppliers',
            'address'                   =>  'required',
            'contact'                   =>  'required',
        ],
        [
            'name.required'             =>  'Supplier Name Required',
            'address.required'          =>  'Supplier Address Required',
            'contact.required'          =>  'Supplier Contact # Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Supplier::create(Request::all());

        toastr()->success('Supplier Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update($id){
        $supplier = Supplier::find($id);
        $validator = Validator::make(Request::all(), [
            'name'                      =>  "required|unique:suppliers,name,$supplier->id,id",
            'address'                   =>  'required',
            'contact'                   =>  'required',
        ],
        [
            'name.required'             =>  'Supplier Name Required',
            'address.required'          =>  'Supplier Address Required',
            'contact.required'          =>  'Supplier Contact # Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $supplier->update(Request::all());

        toastr()->success('Supplier Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }
}
