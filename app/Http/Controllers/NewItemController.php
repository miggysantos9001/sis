<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Product;
use App\Supplier;
use App\New_item;

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

class NewItemController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        if (Request::ajax()) {
            $data = New_item::latest()->get();
            return Datatables::of($data)
            ->editColumn('date', function($data){
                return Carbon::parse($data->date)->toFormattedDateString();
            })

            ->editColumn('expiry_date', function($data){
                return Carbon::parse($data->expiry_date)->toFormattedDateString();
            })

            ->editColumn('supplier_id', function($data){
                return $data->supplier->name;
            })

            ->editColumn('branch_id', function($data){
                return $data->branch->name;
            })

            ->editColumn('product_id', function($data){
                return $data->product->description;
            })

            ->addColumn('action', function($data){
                $btn = '<center>
                <span data-toggle="tooltip" data-placement="top" title="Edit Entry">
                    <a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
                </span>
                </center>';

                return $btn;
            })
            ->rawColumns(['action','supplier_id','branch_id','product_id'])
            ->make(true);
        }
        
        $newitems = New_item::orderBy('date')->get();
        $suppliers = Supplier::orderBy('name')->get()->pluck('SupplierName','id');
        $branches = Branch::orderBy('name')->get()->pluck('BranchName','id');
        $products = Product::orderBy('description')->get()->pluck('ProductName','id');
        $units = \App\Unit::orderBy('name')->get()->pluck('name','id');
        return view('admin.new-items.index',compact('newitems','suppliers','branches','products','units'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'date'                      =>          'required',
            'branch_id'                 =>          'required',
            'supplier_id'               =>          'required',
            'product_id'                =>          'required',
            'qty'                       =>          'required|numeric',
            'expiry_date'               =>          'required',
            'unit_id'                   =>          'required',
            'lot_number'                =>          'required',
        ],
        [
            'date.required'             =>          'Date Required',
            'branch_id.required'        =>          'Please Select Branch',
            'supplier_id.required'      =>          'Please Supplier Branch',
            'product_id.required'       =>          'Please Product Branch',
            'qty.required'              =>          'Quantity Required',
            'expiry_date.required'      =>          'Expiry Date Required',
            'unit_id.required'          =>          'Please Select Unit of Measure',
            'lot_number.required'       =>          'Lot # Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        New_item::create(Request::all());

        toastr()->success('New Item Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update($id){
        $newitem = New_item::find($id);
        $validator = Validator::make(Request::all(), [
            'date'                      =>          'required',
            'branch_id'                 =>          'required',
            'supplier_id'               =>          'required',
            'product_id'                =>          'required',
            'qty'                       =>          'required|numeric',
            'expiry_date'               =>          'required',
            'unit_id'                   =>          'required',
            'lot_number'                =>          'required',
        ],
        [
            'date.required'             =>          'Date Required',
            'branch_id.required'        =>          'Please Select Branch',
            'supplier_id.required'      =>          'Please Supplier Branch',
            'product_id.required'       =>          'Please Product Branch',
            'qty.required'              =>          'Quantity Required',
            'expiry_date.required'      =>          'Expiry Date Required',
            'unit_id.required'          =>          'Please Select Unit of Measure',
            'lot_number.required'       =>          'Lot # Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $newitem->update(Request::all());

        toastr()->success('New Item Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }
}
