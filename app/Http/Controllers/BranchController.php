<?php

namespace App\Http\Controllers;

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

class BranchController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        if (Request::ajax()) {
            $data = Branch::latest()->get();
            return Datatables::of($data)
            ->addColumn('action', function($data){
                $btn = '<center>
                <span data-toggle="tooltip" data-placement="top" title="Edit Branch">
                    <a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
                </span>
                </center>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        $branches = Branch::orderBy('name')->get();
        return view('admin.branches.index',compact('branches'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'name'                      =>  'required|unique:branches',
        ],
        [
            'name.required'             =>  'Branch Name Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Branch::create([
            'name'          =>      Request::get('name'),
            'cname'         =>      Request::get('cname'),
        ]);

        toastr()->success('Branch Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update($id){
        $branch = Branch::find($id);
        $validator = Validator::make(Request::all(), [
            'name'                      =>  "required|unique:branches,name,$branch->id,id",
        ],
        [
            'name.required'             =>  'Branch Name Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $branch->update([
            'name'          =>      Request::get('name'),
            'cname'         =>      Request::get('cname'),
        ]);

        toastr()->success('Branch Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }
}
