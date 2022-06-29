<?php

namespace App\Http\Controllers;

use App\Customer;
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

class CustomerController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $data = Customer::latest()->get();
        if (Request::ajax()) {
            return Datatables::of($data)
            ->addColumn('action', function($data){
                $btn = '<center>
                <span data-toggle="tooltip" data-placement="top" title="Edit Customer">
                    <a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
                </span>
                </center>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.customers.index',compact('data'));
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
            'company_name'              =>  'required',
            'contact_person'            =>  'required',
            'address'                   =>  'required',
            'mobile'                    =>  'required',
            'tin_number'                =>  'required',
        ],
        [
            'company_name.required'     =>  'Company Name Required',
            'contact_person.required'   =>  'Contact Person Required',
            'address.required'          =>  'Address Required',
            'mobile.required'           =>  'Contact Number Required',
            'tin_number.required'       =>  'TIN Number Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Customer::create(Request::all());

        toastr()->success('Customer Created Successfully', config('global.system_name'));
        return redirect()->back();
    }

    public function update($id){
        $customer = Customer::find($id);
        $validator = Validator::make(Request::all(), [
            'company_name'              =>  'required',
            'contact_person'            =>  'required',
            'address'                   =>  'required',
            'mobile'                    =>  'required',
            'tin_number'                =>  'required',
        ],
        [
            'company_name.required'     =>  'Company Name Required',
            'contact_person.required'   =>  'Contact Person Required',
            'address.required'          =>  'Address Required',
            'mobile.required'           =>  'Contact Number Required',
            'tin_number.required'       =>  'TIN Number Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $customer->update(Request::all());

        toastr()->success('Customer Updated Successfully', config('global.system_name'));
        return redirect()->back();
    }
}
