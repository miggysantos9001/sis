<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Product;
use App\Supplier;
use App\New_item;
use App\Purchase_order;
use App\Purchase_order_item;
use App\Purchase_order_item_total;
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
        $data = Purchase_order::latest()->get();
        if (Request::ajax()) {
            //$data = Purchase_order::latest()->get();
            return Datatables::of($data)
            ->editColumn('date', function($data){
                return Carbon::parse($data->date)->toFormattedDateString();
            })

            ->editColumn('supplier_id', function($data){
                return $data->supplier->name;
            })

            ->editColumn('branch_id', function($data){
                return $data->branch->name;
            })

            ->addColumn('action', function($data){
                $btn = '<center>
                <span data-toggle="tooltip" data-placement="top" title="Edit Entry">
                    <a href="'.route('new-items.edit',$data->id).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                    <a href="'.route('new-items.delete',$data->id).'" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                </span>
                </center>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.new-items.index',compact('data'));
    }

    public function create(){
        $suppliers = Supplier::orderBy('name')->get()->pluck('SupplierName','id');
        $branches = Branch::orderBy('name')->get()->pluck('name','id');
        $products = Product::orderBy('description')->get()->pluck('ProductName','id');
        $units = \App\Unit::orderBy('name')->get()->pluck('name','id');
        return view('admin.new-items.create',compact('suppliers','branches','products','units'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'date'                      =>          'required',
            'po_number'                 =>          'required|unique:purchase_orders',
            'branch_id'                 =>          'required',
            'supplier_id'               =>          'required',
            'product_id.*'                =>          'required',
            'qty.*'                       =>          'required|numeric',
            'expiry_date.*'               =>          'required',
            'uom_id.*'                   =>          'required',
            'lot_number.*'                =>          'required',
        ],
        [
            'date.required'             =>          'Date Required',
            'po_number.required'        =>          'PO # Required',
            'branch_id.required'        =>          'Please Select Branch',
            'supplier_id.required'      =>          'Please Supplier',
            'product_id.*.required'       =>          'Please Product',
            'qty.*.required'              =>          'Quantity Required',
            'expiry_date.*.required'      =>          'Expiry Date Required',
            'uom_id.*.required'          =>          'Please Select Unit of Measure',
            'lot_number.*.required'       =>          'Lot # Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $po_id = Purchase_order::create([
            'date'              =>      Request::get('date'),
            'po_number'         =>      Request::get('po_number'),
            'supplier_id'       =>      Request::get('supplier_id'),
            'branch_id'         =>      Request::get('branch_id'),
        ])->id;
        
        foreach(Request::get('product_id') as $key => $value){
            $poi = Purchase_order_item::create([
                'purchase_order_id'     =>      $po_id,
                'date'                  =>      Request::get('date'),
                'product_id'            =>      $value,
                'uom_id'                =>      Request::get('uom_id')[$key],
                'lot_number'            =>      Request::get('lot_number')[$key],
                'expiry_date'           =>      Request::get('expiry_date')[$key],
                'qty'                   =>      Request::get('qty')[$key],
            ])->id;

            Purchase_order_item_total::create([
                'purchase_order_item_id'        =>      $poi,
                'original_qty'                  =>      Request::get('qty')[$key],
            ]);
        }

        toastr()->success('Purchase Order Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function edit($id){
        $po = Purchase_order::find($id);
        $suppliers = Supplier::orderBy('name')->get()->pluck('SupplierName','id');
        $branches = Branch::orderBy('name')->get()->pluck('name','id');
        $products = Product::orderBy('description')->get()->pluck('ProductName','id');
        $units = \App\Unit::orderBy('name')->get()->pluck('name','id');
        return view('admin.new-items.edit',compact('po','suppliers','branches','products','units'));
    }

    public function update($id){
        $po = Purchase_order::find($id);
        $validator = Validator::make(Request::all(), [
            'date'                      =>          'required',
            'po_number'                 =>          "required|unique:purchase_orders,po_number,$po->id,id",
            'branch_id'                 =>          'required',
            'supplier_id'               =>          'required',
        ],
        [
            'date.required'             =>          'Date Required',
            'po_number.required'        =>          'PO # Required',
            'branch_id.required'        =>          'Please Select Branch',
            'supplier_id.required'      =>          'Please Supplier',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $po->update([
            'date'              =>      Request::get('date'),
            'po_number'         =>      Request::get('po_number'),
            'supplier_id'       =>      Request::get('supplier_id'),
            'branch_id'         =>      Request::get('branch_id'),
        ]);
        
        foreach(Request::get('product_id') as $key => $value){
            if(!empty($value)){
                Purchase_order_item::create([
                    'purchase_order_id'     =>      $po->id,
                    'date'                  =>      Request::get('date'),
                    'product_id'            =>      $value,
                    'uom_id'                =>      Request::get('uom_id')[$key],
                    'lot_number'            =>      Request::get('lot_number')[$key],
                    'expiry_date'           =>      Request::get('expiry_date')[$key],
                    'qty'                   =>      Request::get('qty')[$key],
                ]);
            }
        }

        toastr()->success('Purchase Order Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update_item($id){
        $item = Purchase_order_item::find($id);
        $validator = Validator::make(Request::all(), [
            'product_id'                =>          'required',
            'qty'                       =>          'required|numeric',
            'expiry_date'               =>          'required',
            'uom_id'                   =>          'required',
            'lot_number'                =>          'required',
        ],
        [
            'product_id.required'       =>          'Please Product',
            'qty.required'              =>          'Quantity Required',
            'expiry_date.required'      =>          'Expiry Date Required',
            'uom_id.required'          =>          'Please Select Unit of Measure',
            'lot_number.required'       =>          'Lot # Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $item->update(Request::all());
        toastr()->success('Purchase Order Item Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function delete_item($id){
        $item = Purchase_order_item::find($id);
        $item->delete();
        toastr()->success('Purchase Order Item Deleted Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function delete($id){
        $po = Purchase_order::find($id);
        $po->po_items()->delete();
        $po->delete();
        toastr()->success('Purchase Order Deleted Successfully', config('global.system_name'));
        return redirect()->back();
    }
}
