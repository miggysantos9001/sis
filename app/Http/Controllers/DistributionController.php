<?php

namespace App\Http\Controllers;

use App\Consumed_product;
use App\Customer;
use App\Distribution;
use App\Distribution_item;
use App\Distribution_payment;
use App\Product;
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

class DistributionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $distributions = Distribution::with('customer','distribution_items')
            ->orderBy('date','DESC')
            ->get();
        
        return view('admin.distributions.index',compact('distributions'));
    }

    public function loadproducts(){
        $id = Request::get('combobox3');
        $prod = Purchase_order_item::with(['product','product.pricing'])->where('product_id',$id)->where('isConsumed',0)->orderBy('date')->get();    
        return $prod;
    }

    public function create(){
        //return Session::forget('cart');
        $customers = Customer::orderBy('company_name')->get()->pluck('company_name','id');
        $products = Product::orderBy('description')->get()->pluck('description','id');
        return view('admin.distributions.create',compact('customers','products'));
    }

    public function addtoCart(){
        $cart = Session::get('cart', []);
        foreach(Request::get('products') as $key => $value){
            if(!empty($value['purchase_order_item_id'])){
                $product = Purchase_order_item::with('product','product.pricing')->where('id',$value['purchase_order_item_id'])->first();
                $cart[$value['purchase_order_item_id']] = [
                    "purchase_order_item_id"    =>  $value['purchase_order_item_id'],
                    "product_id"                =>  $product->product_id,
                    "product_name"              =>  $product->product->description,
                    "uom_id"                    =>  $product->product->unit->name,
                    "lot_number"                =>  $product->lot_number,
                    "expiry_date"               =>  $product->expiry_date,
                    "po_qty"                    =>  $product->qty,
                    "qty"                       =>  $value['qty'],
                    "amount"                    =>  $product->product->pricing->wsp,
                ];
            }
        }
            
        Session::put('cart', $cart);
        toastr()->success('Product Added to Cart Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function removetoCart($id){
        $cart = Session::get('cart');
        unset($cart[$id]);
        Session::put('cart', $cart);
        toastr()->success('Product Removed to Cart Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function create_customer_order(){
        $customers = Customer::orderBy('company_name')->get()->pluck('company_name','id');
        $products = Product::orderBy('description')->get()->pluck('description','id');
        return view('admin.distributions.create-customer-order',compact('customers','products'));
    }

    public function store_customer_order(){
        $validator = Validator::make(Request::all(), [
            'date'                          =>  'required',
            'reference_number'              =>  'required|unique:distributions',
            'terms'                         =>  'required',
            'customer_id'                   =>  'required',
            'representative'                =>  'required',
        ],
        [
            'date.required'                 =>  'Date Required',
            'reference_number.required'     =>  'Reference Number Required',
            'terms.required'                =>  'Please Select Terms',
            'customer_id.required'          =>  'Please Select Customer',
            'representative.required'       =>  'Representative Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $distribution_id = Distribution::create(Request::except('purchase_order_item_id','qty'))->id;
        foreach(Request::get('purchase_order_item_id') as $key => $value){
            Distribution_item::create([
                'date'                      =>          Request::get('date'),
                'distribution_id'           =>          $distribution_id,
                'purchase_order_item_id'    =>          $value,
                'qty'                       =>          Request::get('qty')[$key],
            ]);

            Consumed_product::create([
                'purchase_order_item_id'    =>          $value,
                'consumed_qty'              =>          Request::get('qty')[$key],
            ]);

            $checkqty = Purchase_order_item::where('id',$value)->first();

            Purchase_order_item::where('id',$value)->update([
                'qty'                       =>      $checkqty->qty - Request::get('qty')[$key],
            ]);

            $getSum = Consumed_product::where('purchase_order_item_id',$value)->sum('consumed_qty');
            $getOrig = Purchase_order_item_total::where('purchase_order_item_id',$value)->sum('original_qty');
            if($getSum == $getOrig){
                $checkqty->update([
                    'isConsumed'        =>      1,
                ]);
            }
        }

        toastr()->success('Distribution Order Created Succesfully', config('global.system_name'));
        Session::forget('cart');
        return redirect()->route('distributions.index');
        
    }

    public function edit($id){
        $distribution = Distribution::find($id);
        $customers = Customer::orderBy('company_name')->get()->pluck('company_name','id');
        return view('admin.distributions.edit',compact('customers','distribution'));
    }

    public function update($id){
        $distribution = Distribution::find($id);
        $distribution->update(Request::all());
        toastr()->success('Distribution Order Updated Succesfully', config('global.system_name'));
        return redirect()->back();
    }

    public function delete($id){
        $distribution = Distribution::find($id);
        if($distribution->distribution_items->count() > 0){
            $distribution->distribution_items()->delete();
        }
        $distribution->delete();
        toastr()->success('Distribution Order Deleted Succesfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update_entry($id){
        $item = Distribution_item::find($id);
        $item->update([
            'qty'       =>      Request::get('qty'),
        ]);

        toastr()->success('Distribution Order Item Updated Succesfully', config('global.system_name'));
        return redirect()->back();
    }

    public function delete_entry($id){
        $item = Distribution_item::find($id);
        $item->delete();

        toastr()->success('Distribution Order Item Deleted Succesfully', config('global.system_name'));
        return redirect()->back();
    }

    public function set_paid($id){
        $distribution = Distribution::find($id);
        return view('admin.distributions.set-paid',compact('distribution'));
    }

    public function post_set_paid($id){
        $distribution = Distribution::find($id);
        $validator = Validator::make(Request::all(), [
            'date'                          =>  'required',
            'receipt_number'                =>  'required',
            'payment_type'                  =>  'required',
            'amount'                        =>  'required',
            //'remarks'                       =>  'required',
        ],
        [
            'date.required'                 =>  'Date Required',
            'receipt_number.required'       =>  'Receipt Number Required',
            'payment_type.required'         =>  'Please Select Payment Type',
            'amount.required'               =>  'Amount Required',
            //'remarks.required'       =>  'Representative Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Distribution_payment::updateOrCreate([
            'distribution_id'           =>      $distribution->id,
        ],[
            'date'                      =>      Request::get('date'),
            'receipt_number'            =>      Request::get('receipt_number'),
            'payment_type'              =>      Request::get('payment_type'),
            'amount'                    =>      Request::get('amount'),
            'remarks'                   =>      Request::get('remarks'),
        ]);

        $distribution->update([
            'isPaid'        =>      1,
        ]);

        toastr()->success('Payment Updated Succesfully', config('global.system_name'));
        return redirect()->back();
    }

    public function print($id){
        $distribution = Distribution::find($id);
        return View('admin.pdf.distribution-report',compact('distribution'));
    }
}
