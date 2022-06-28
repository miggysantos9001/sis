<?php

namespace App\Http\Controllers;

use App\Branch;
use App\User;
use App\Cashier;

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

class CashierController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        if (Request::ajax()) {
            $data = Cashier::latest()->get();
            return Datatables::of($data)
            ->editColumn('branch_id', function($data){
                $bn = $data->branch->name;
                return $bn;
            })
            ->editColumn('name', function($data){
                $bn = $data->name;
                return $bn;
            })
            ->addColumn('action', function($data){
                $btn = '<center>
                <span data-toggle="tooltip" data-placement="top" title="Edit Cashier">
                    <a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
                </span>
                </center>';

                return $btn;
            })
            ->rawColumns(['action','name'])
            ->make(true);
        }
        $cashiers = Cashier::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get()->pluck('BranchName','id');
        return view('admin.cashiers.index',compact('cashiers','branches'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'cashier_name'              =>  'required',
            'name'                      =>  'required|unique:users',
            'branch_id'                 =>  'required',
        ],
        [
            'cashier_name.required'     =>  'Cashier Name Required',
            'name.required'             =>  'User Name Required',
            'branch_id.required'        =>  'Please Select Branch',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cashier_id = Cashier::create([
            'name'          =>      Request::get('cashier_name'),
            'cname'          =>      Request::get('cname'),
            'branch_id'     =>      Request::get('branch_id'),
        ])->id;

        User::create([
            'cashier_id'    =>      $cashier_id,
            'name'          =>      Request::get('name'),
            'password'      =>      \Hash::make(preg_replace('/\s+/', '',strtolower(Request::get('name')))),
            'branch_id'     =>      Request::get('branch_id'),      
        ]);

        toastr()->success('Cashier Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update($id){
        $cashier = Cashier::find($id);
        $validator = Validator::make(Request::all(), [
            'cashier_name'              =>  "required|unique:cashiers,name,$cashier->id,id",
            'branch_id'                 =>  'required',
        ],
        [
            'name.required'             =>  'Cashier Name Required',
            'branch_id.required'        =>  'Please Select Branch',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cashier->update([
            'name'          =>      Request::get('cashier_name'),
            'cname'         =>      Request::get('cname'),
            'branch_id'     =>      Request::get('branch_id'),
        ]);

        toastr()->success('Cashier Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }
}
