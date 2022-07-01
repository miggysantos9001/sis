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
        $customers = Customer::orderBy('company_name')->get()->pluck('company_name','id');
        $products = Product::orderBy('description')->get()->pluck('description','id');
        return view('admin.distributions.create',compact('customers','products'));
    }

    public function store(){

    }
}
