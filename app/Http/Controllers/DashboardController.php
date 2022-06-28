<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use App\Sale;
use App\Sale_item;
use App\Sale_transaction;

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

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        if(Auth::user()->cashier_id != NULL){
            $products = Product::orderBy('description')->get()->pluck('ProductName','id');
            $cashier = Auth::user()->cashier_id;
            $date = Carbon::now();

            $checkSaleTrans = Sale_transaction::where('cashier_id',$cashier)
                ->where('date',Carbon::now()->toDateString())
                ->count();

            if($checkSaleTrans > 0){
                $voucher_data = $checkSaleTrans + 1;
            }else{
                $voucher_data = Sale::where('cashier_id',$cashier)
                    ->where('date',$date)
                    ->count() + 1;
            }

            $transcode = Auth::user()->branch->name.'-'.$date->year.'-'.$date->format('m').$date->format('d').'-'.$cashier.'-'.$voucher_data;

            return view('dashboard',compact('products','transcode','cashier'));
        }else{

            $branches = \App\Branch::orderBy('name')->get();
            return view('dashboard',compact('branches'));
        }
        
    }

    public function store_items(){
        $transcode = Request::get('transcode');
        $cashier = Request::get('cashier_id');

        $branch = \App\Cashier::where('id',$cashier)->first();

        $checkSale = Sale::where('cashier_id',$cashier)
            ->where('transaction_code',$transcode)
            ->first();

        if($checkSale == NULL){
            $sale_id = Sale::create([
                'date'              =>      Carbon::now()->toDateString(),
                'cashier_id'        =>      $cashier,
                'transaction_code'  =>      $transcode,
            ])->id;

            $unit_price = \App\Product_pricing::where('product_id',Request::get('product_id'))
                ->where('branch_id',$branch->branch_id)
                ->orderBy('id','DESC')
                ->first();

            if(Request::get('isWSP') == 1){
                $up = $unit_price->wsp;
                $wsp = 1;
            }else{
                $up = $unit_price->srp;
                $wsp = 0;
            }

            Sale_item::create([
                'date'              =>      Carbon::now()->toDateString(),
                'sale_id'           =>      $sale_id,
                'branch_id'         =>      $branch->branch_id,
                'product_id'        =>      Request::get('product_id'),
                'qty'               =>      Request::get('qty'),
                'unit_price'        =>      $up,
                'wsp'               =>      $wsp,
                ]);
        }else{
            $unit_price = \App\Product_pricing::where('product_id',Request::get('product_id'))
                ->orderBy('id','DESC')
                ->first();

            if(Request::get('isWSP') == 1){
                $up = $unit_price->wsp;
                $wsp = 1;
            }else{
                $up = $unit_price->srp;
                $wsp = 0;
            }

            Sale_item::create([
                'date'              =>      Carbon::now()->toDateString(),
                'sale_id'           =>      $checkSale->id,
                'branch_id'         =>      $branch->branch_id,
                'product_id'        =>      Request::get('product_id'),
                'qty'               =>      Request::get('qty'),
                'unit_price'        =>      $up,
                'wsp'               =>      $wsp,
                ]);
        }

        return redirect()->back();
    }

    public function cancel_item($id){
        $item = Sale_item::find($id);
        $item->delete();
        return redirect()->back();
    }

    public function save_transaction($id){
        $sale = Sale::find($id);

        $discount = Request::get('discount');

        if($discount == NULL){
            $pdesc = "NONE";
            $pdiscount = 0;
        }else{
            if($discount == 0){
                $pdesc = "PERCENTAGE";
                $pdiscount = (Request::get('total') * (Request::get('discount_value') / 100));
            }else{
                $pdesc = "LESS PESOS";
                $pdiscount = Request::get('discount_value');
            }
        }


        Sale_transaction::create([
            'date'          =>      Carbon::now()->toDateString(),
            'sale_id'       =>      $sale->id,
            'cashier_id'    =>      Auth::user()->cashier_id,
            'branch_id'     =>      $sale->cashier->branch_id,
            'pdesc'         =>      $pdesc,
            'pdiscount'     =>      $pdiscount,
            'total'         =>      Request::get('total'),
            'paid'          =>      Request::get('paid'),
            'amount_change' =>      Request::get('paid') - Request::get('total'),
        ]); 

        toastr()->success('Sale Transaction Added Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function changepassword($id){
        $user = \App\User::find(Auth::user()->id);
        return view('changepassword',compact('user'));
    }

    public function post_changepassword($id){
        $user = \App\User::find(Auth::user()->id);
        $validator = Validator::make(Request::all(), [
            'password'              =>  'required',
        ],
        [
            'password.required'     =>  'Password Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user->update([
            'password'  =>      \Hash::make(preg_replace('/\s+/', '',strtolower(Request::get('password')))),
        ]);

        toastr()->success('Password Changed Successfully', config('global.system_name'));
        return redirect()->back();
    }

    
}
