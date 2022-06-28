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
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function view_inventory(){
        $categories = \App\Category::orderBy('name')->get()->pluck('CategoryName','id');
        $branches = \App\Branch::orderBy('name')->get()->pluck('Branch Name','id');
        return view('admin.inventories.view',compact('categories','branches'));
    }

    public function post_inventory(){
        $validator = Validator::make(Request::all(), [
            'branch_id'                     =>  'required',
            'category_id'                   =>  'required',
        ],
        [
            'branch_id.required'            =>  'Please Select Branch',
            'category_id.required'          =>  'Please Select Category',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $branch = \App\Branch::find(Request::get('branch_id'));
        $category = \App\Category::find(Request::get('category_id'));

        $list = [];

        $products = \App\Product::where('category_id',$category->id)->orderBy('description')->get();
        
        foreach($products as $p){
            $sales = \App\Sale_item::where('product_id',$p->id)->sum('qty');
            $purchases = \App\New_item::where('product_id',$p->id)->sum('qty');
            $list[] = array(
                $category->name.' / '.$category->cname,
                $p->barcode,
                $p->description.' / '.$p->cdescription,
                $purchases,
                $sales,
                $purchases - $sales,
            );
        }

        //dump($list);

        $setting = \App\Systemsetting::where('branch_id',$branch->id)->first();
        return View('admin.pdf.inventory', compact('branch','category','setting','list'));
    }
}
