<?php

namespace App\Http\Controllers;

use App\Product;
use App\Product_pricing;
use App\Product_image;
use App\Category;

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

class ProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        if (Request::ajax()) {
            $data = Product::latest()->get();
            return Datatables::of($data)
            ->editColumn('category_id', function($data){
                $bn = $data->category->name ."<br><small>".$data->category->cname."</small>";
                return $bn;
            })

            ->editColumn('description', function($data){
                $bn = $data->description ."<br><small>".$data->cdescription."</small>";
                return $bn;
            })

            ->addColumn('img', function($data){
                $img = \App\Product_image::where('product_id',$data->id)
                    ->first();

                if($img == NULL){
                    $url= asset('public/images/noimage.png');
                }else{
                    $url= asset('public/images/'.$img->name);
                }

                return '<center><img class="img-avatar" src='.$url.' alt=""></center>';
            })

            ->addColumn('action', function($data){
                $btn = '<center><div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list"></i></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">';
                $btn .='<a href="'.action('ProductController@view_product',$data->id).'" class="dropdown-item"> '.__('msg.View Product').'</a>';
                $btn .='<a href="'.action('ProductController@edit',$data->id).'" class="dropdown-item"> '.__('msg.Edit Product').'</a>';
                        
                $btn .='</div></div></center>';

                return $btn;
            })
            ->rawColumns(['action','img','category_id','description'])
            ->make(true);
        }

        return view('admin.products.index');
    }

    public function create(){
        $categories = Category::orderBy('name')->get()->pluck('CategoryName','id');
        $branches = \App\Branch::orderBy('name')->get()->pluck('BranchName','id');
        return view('admin.products.create',compact('categories','branches'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'barcode'                       =>  'required|unique:products',
            'category_id'                   =>  'required',
            'branch_id'                     =>  'required',
            'description'                   =>  'required|unique:products',
            'stp'                           =>  'required|numeric',
            'srp'                           =>  'required|numeric',
            'wsp'                           =>  'required|numeric',
            'images'                        =>  "required|array|min:1",
        ],
        [
            'barcode.required'              =>  'Barcode # Required',
            'category_id.required'          =>  'Please Select Category',
            'branch_id.required'            =>  'Please Select Branch',
            'description.required'          =>  'Description Required',
            'stp.required'                  =>  'Original Price Required',
            'srp.required'                  =>  'SRP Required',
            'wsp.required'                  =>  'Wholesale Price Required',
            'images.required'               =>  'Upload Product Images',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product_id = Product::create([
                'barcode'           =>      Request::get('barcode'),
                //'branch_id'         =>      Request::get('branch_id'),
                'category_id'       =>      Request::get('category_id'),
                'description'       =>      Request::get('description'),
                'cdescription'      =>      Request::get('cdescription'),
        ])->id;

        Product_pricing::create([
            'product_id'            =>      $product_id,
            'branch_id'             =>      Request::get('branch_id'),
            'stp'                   =>      Request::get('stp'),
            'srp'                   =>      Request::get('srp'),
            'wsp'                   =>      Request::get('wsp'),
        ]);

        $images=array();
        if($files = Request::file('images')){
            foreach($files as $file){
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::random(40).'.'.$file->extension();
                $file->move(public_path().'/images',$fileName);

                Product_image::create([
                    'product_id'        =>      $product_id,
                    'name'              =>      $fileName,
                ]);
            }
        }

        toastr()->success('Product Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function edit($id){
        $product = Product::find($id);
        $categories = Category::orderBy('name')->get()->pluck('CategoryName','id');
        return view('admin.products.edit',compact('categories','product'));
    }

    public function update($id){
        $product = Product::find($id);
        $validator = Validator::make(Request::all(), [
            'barcode'                       =>  "required|unique:products,barcode,$product->id,id",
            'category_id'                   =>  'required',
            'description'                   =>  "required|unique:products,description,$product->id,id",
        ],
        [
            'barcode.required'              =>  'Barcode # Required',
            'category_id.required'          =>  'Please Select Category',
            'description.required'          =>  'Description Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product->update([
                'barcode'           =>      Request::get('barcode'),
                'category_id'       =>      Request::get('category_id'),
                'description'       =>      Request::get('description'),
                'cdescription'      =>      Request::get('cdescription'),
        ]);

        $images=array();
        if(Request::has('images')){
            if($files = Request::file('images')){
                foreach($files as $file){
                    $extension = $file->getClientOriginalExtension();
                    $fileName = Str::random(40).'.'.$file->extension();
                    $file->move(public_path().'/images',$fileName);

                    Product_image::create([
                        'product_id'        =>      $product->id,
                        'name'              =>      $fileName,
                    ]);
                }
            }
        }

        toastr()->success('Product Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function view_product($id){
        $product = Product::find($id);
        $branches = \App\Branch::orderBy('name')->get();
        $branch = \App\Branch::orderBy('name')->get()->pluck('BranchName','id');
        return view('admin.products.view',compact('product','branches','branch'));
    }

    public function new_price($id){
        $product = Product::find($id);
        $validator = Validator::make(Request::all(), [
            'branch_id'                     =>  'required',
            'stp'                           =>  'required|numeric',
            'srp'                           =>  'required|numeric',
            'wsp'                           =>  'required|numeric',
        ],
        [
            'branch_id.required'            =>  'Please Select Branch',
            'stp.required'                  =>  'Original Price Required',
            'srp.required'                  =>  'SRP Required',
            'wsp.required'                  =>  'Wholesale Price Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product->pricing->create([
            'product_id'            =>      $product->id,
            'branch_id'             =>      Request::get('branch_id'),
            'stp'                   =>      Request::get('stp'),
            'srp'                   =>      Request::get('srp'),
            'wsp'                   =>      Request::get('wsp'),
        ]);

        toastr()->success('Product Price Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function delete_product_image($id){
        $img = \App\Product_image::find($id);
        $img->delete();
        toastr()->success('Product Image Deleted Successfully', config('global.system_name'));
        return redirect()->back();
    }
}
