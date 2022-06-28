<?php

namespace App\Http\Controllers;

use App\Sale;
use App\Branch;

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
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function view_daily_sales(){
        $branches = Branch::orderBy('name')->get()->pluck('BranchName','id');
        return view('admin.sales.daily-sales',compact('branches'));
    }

    public function post_daily_sales(){
        $validator = Validator::make(Request::all(), [
            'branch_id'                     =>  'required',
            'date_from'                     =>  'required',
            'date_to'                       =>  'required',
        ],
        [
            'branch_id.required'            =>  'Please Select Branch',
            'date_from.required'            =>  'Date From Required',
            'date_to.required'              =>  'Date To Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $branch = \App\Branch::find(Request::get('branch_id'));
        $date_from = Request::get('date_from');
        $date_to = Request::get('date_to');

        $sales = \App\Sale_transaction::where('branch_id',Request::get('branch_id'))
                ->where('date','>=',Request::get('date_from'))
                ->where('date','<=',Request::get('date_to'))
                ->orderBy('date')
                ->get();

        $list = [];
        foreach($sales as $s){
            $list[] = array(
                Carbon::parse($s->date)->toFormattedDateString(),
                $s->sale->transaction_code,
                $s->branch->name .' / '. $s->branch->cname,
                $s->cashier->name .' / '. $s->cashier->cname,
                $s->total,
                $s->pdesc,
                $s->pdiscount,
                $s->total - $s->pdiscount,
            );
        }

        $setting = \App\Systemsetting::where('branch_id',$branch->id)->first();

        return View('admin.pdf.daily-sales', compact('list','branch','date_from','date_to','setting'));

    }

    public function view_product_status(){
        $branches = Branch::orderBy('name')->get()->pluck('BranchName','id');
        $products = \App\Product::orderBy('description')->get()->pluck('ProductName','id');
        return view('admin.sales.product-status',compact('products','branches'));
    }

    public function post_product_status(){
        $validator = Validator::make(Request::all(), [
            'branch_id'                     =>  'required',
            'product_id'                    =>  'required',
            'date_from'                     =>  'required',
            'date_to'                       =>  'required',
        ],
        [
            'branch_id.required'            =>  'Please Select Branch',
            'product_id.required'           =>  'Please Select Product',
            'date_from.required'            =>  'Date From Required',
            'date_to.required'              =>  'Date To Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product = \App\Product::find(Request::get('product_id'));
        $branch = \App\Branch::find(Request::get('branch_id'));
        $date_from = Request::get('date_from');
        $date_to = Request::get('date_to');

        $list_sales = $list_purchases = [];

        $sales = \App\Sale_item::where('product_id',$product->id)
            ->where('branch_id',$branch->id)
            ->where('date','>=',Request::get('date_from'))
            ->where('date','<=',Request::get('date_to'))
            ->orderBy('date')
            ->get();

        $purchases = \App\New_item::where('product_id',$product->id)
            ->where('branch_id',$branch->id)
            ->where('date','>=',Request::get('date_from'))
            ->where('date','<=',Request::get('date_to'))
            ->orderBy('date')
            ->get();

        foreach($sales as $s){
            $list_sales[] = array(
                Carbon::parse($s->date)->toFormattedDateString(),
                $s->sale->transaction_code,
                $s->branch->name.' / '.$s->branch->cname,
                $s->sale->cashier->name.' / '.$s->sale->cashier->xname,
                $s->product->barcode,
                $s->product->description.' / '.$s->product->cdescription,
                $s->qty,
                $s->unit_price,
                ($s->wsp == 1) ? 'WSP' : 'SRP',
                number_format($s->qty * $s->unit_price,2),
            );
        }

        foreach($purchases as $p){
            $list_purchases[] = array(
                Carbon::parse($p->date)->toFormattedDateString(),
                $p->branch->name.' / '.$p->branch->cname,
                $p->supplier->name.' / '.$p->supplier->cname,
                $p->product->barcode,
                $p->product->description.' / '.$p->product->cdescription,
                Carbon::parse($p->expiry_date)->toFormattedDateString(),
                $p->qty,
            );
        }

        $setting = \App\Systemsetting::where('branch_id',$branch->id)->first();
        return View('admin.pdf.product-status', compact('product','branch','date_from','date_to','setting','list_sales','list_purchases'));
    }

    public function view_sales_per_date(){
        $branches = Branch::orderBy('name')->get()->pluck('BranchName','id');
        return view('admin.sales.sales-per-date',compact('branches'));
    }

    public function post_sales_per_date(){
        $validator = Validator::make(Request::all(), [
            'branch_id'                     =>  'required',
            'date_from'                     =>  'required',
            'date_to'                       =>  'required',
        ],
        [
            'branch_id.required'            =>  'Please Select Branch',
            'date_from.required'            =>  'Date From Required',
            'date_to.required'              =>  'Date To Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $branch = \App\Branch::find(Request::get('branch_id'));
        $date_from = Request::get('date_from');
        $date_to = Request::get('date_to');

        $begin = new \DateTime($date_from);
        $end = new \DateTime($date_to);

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        $setting = \App\Systemsetting::where('branch_id',$branch->id)->first();
        return View('admin.pdf.sales-per-date', compact('branch','date_from','date_to','setting','period'));
    }

    public function view_daily_product_sales(){
        $branches = Branch::orderBy('name')->get()->pluck('BranchName','id');
        return view('admin.sales.daily-product-sales',compact('branches'));
    }

    public function post_daily_product_sales(){
        $validator = Validator::make(Request::all(), [
            'branch_id'                     =>  'required',
            'date_from'                     =>  'required',
            'date_to'                       =>  'required',
        ],
        [
            'branch_id.required'            =>  'Please Select Branch',
            'date_from.required'            =>  'Date From Required',
            'date_to.required'              =>  'Date To Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $branch = \App\Branch::find(Request::get('branch_id'));
        $date_from = Request::get('date_from');
        $date_to = Request::get('date_to');

        $sales = \App\Sale_item::with(['product'])->where('branch_id',Request::get('branch_id'))
                ->where('date','>=',Request::get('date_from'))
                ->where('date','<=',Request::get('date_to'))
                ->orderBy('date')
                ->groupBy('product_id')
                ->get()
                ->sortBy('product.description');

        $list = [];

        foreach($sales as $s){
            $count = \App\Sale_item::where('branch_id',Request::get('branch_id'))
                ->where('date','>=',Request::get('date_from'))
                ->where('date','<=',Request::get('date_to'))
                ->where('product_id',$s->product_id)
                ->sum('qty');

            $list[] = array(
                $s->product->barcode,
                $s->product->description.'/'.$s->product->cdescription,
                number_format($count,0),
            );
        }

        $setting = \App\Systemsetting::where('branch_id',$branch->id)->first();
        return View('admin.pdf.daily-product-sales', compact('branch','date_from','date_to','setting','list'));

    }

    public function view_daily_category_product_sales(){
        $categories = \App\Category::orderBy('name')->get()->pluck('CategoryName','id');
        $branches = Branch::orderBy('name')->get()->pluck('BranchName','id');
        return view('admin.sales.daily-category-product-sales',compact('categories','branches'));
    }

    public function post_daily_category_product_sales(){
        $validator = Validator::make(Request::all(), [
            'branch_id'                     =>  'required',
            'category_id'                   =>  'required',
            'date_from'                     =>  'required',
            'date_to'                       =>  'required',
        ],
        [
            'branch_id.required'            =>  'Please Select Branch',
            'category_id.required'          =>  'Please Select Category',
            'date_from.required'            =>  'Date From Required',
            'date_to.required'              =>  'Date To Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $branch = \App\Branch::find(Request::get('branch_id'));
        $category = \App\Category::find(Request::get('category_id'));
        $date_from = Request::get('date_from');
        $date_to = Request::get('date_to');

        $list = [];

        $products = \App\Product::where('category_id',$category->id)->orderBy('description')->get();
        
        foreach($products as $p){
            $items = \App\Sale_item::where('branch_id',$branch->id)
                ->where('product_id',$p->id)
                ->sum('qty');
            if($items > 0){
                $list[] = array(
                    $category->name.' / '.$category->cname,
                    $p->barcode,
                    $p->description.' / '.$p->cdescription,
                    number_format($items,0),
                );
            }
            
        }

        $setting = \App\Systemsetting::where('branch_id',$branch->id)->first();
        return View('admin.pdf.daily-category-product-sales', compact('branch','category','date_from','date_to','setting','list'));
    }

    public function view_daily_income(){

    }


    public function post_daily_income(){
        
    }

}
