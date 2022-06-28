<?php

namespace App\Http\Controllers;

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

class CategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        if (Request::ajax()) {
            $data = Category::latest()->get();
            return Datatables::of($data)
            ->addColumn('action', function($data){
                $btn = '<center>
                <span data-toggle="tooltip" data-placement="top" title="Edit Category">
                    <a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
                </span>
                </center>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.index',compact('categories'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'name'                      =>  'required|unique:categories',
        ],
        [
            'name.required'             =>  'Category Name Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Category::create([
            'name'              =>      Request::get('name'),
            'cname'             =>      Request::get('cname'),
        ]);

        toastr()->success('Category Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update($id){
        $category = Category::find($id);
        $validator = Validator::make(Request::all(), [
            'name'                      =>  "required|unique:categories,name,$category->id,id",
        ],
        [
            'name.required'             =>  'Category Name Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update([
            'name'              =>      Request::get('name'),
            'cname'             =>      Request::get('cname'),
        ]);

        toastr()->success('Category Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }
}
