<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use App\Purchase_order_item;
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

    public function loadproducts(){
        $id = Request::get('combobox3');
        $prod = Purchase_order_item::with('product')->where('product_id',$id)->orderBy('date')->get();    
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
                $product = Purchase_order_item::where('id',$value['purchase_order_item_id'])->first();
                $cart[$value['purchase_order_item_id']] = [
                    "purchase_order_item_id"    =>  $value['purchase_order_item_id'],
                    "product_id"                =>  $product->product_id,
                    "lot_number"                =>  $product->lot_number,
                    "expiry_date"               =>  $product->expiry_date,
                    "po_qty"                    =>  $product->qty,
                    "qty"                       =>  $value['qty'],
                ];
            }
        }
            
        Session::put('cart', $cart);
        toastr()->success('Branch Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function create_customer_order(){
        $customers = Customer::orderBy('company_name')->get()->pluck('company_name','id');
        $products = Product::orderBy('description')->get()->pluck('description','id');
        return view('admin.distributions.create-customer-order',compact('customers','products'));
    }

    public function store_customer_order(){

    }
}
