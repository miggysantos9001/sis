<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Artisan;

use App\User;
use App\Usertype;
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

class UserController extends Controller
{
    public function __construct(){
	    $this->middleware('auth');
	}

	public function index(){
    	if (Request::ajax()) {
	        $data = User::where('status',0)->get();
	        return Datatables::of($data)
	        ->editColumn('usertype_id', function($data){
                return $data->usertype->name;
            })
            ->addColumn('action', function($data){

                   $btn = '<center>
   	               <span data-toggle="tooltip" data-placement="top" title="Edit Area">
   	               <a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
   	               <a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
   	               </span>
   	               </center>';

                    return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
	    $users = User::get();
	    $usertypes = Usertype::orderBy('name')->get()->pluck('name','id');
    	return view('admin.users.index',compact('users','usertypes'));
	}

	public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:users',
		    'password'					=>	'required',
		    'usertype_id'				=>	'required',
		],
		[
		    'name.required'     		=>	'Username Required',
		    'password.required'			=>	'Password Required',
		    'usertype_id.required'		=>	'Please Select Usertype',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		User::create([
			'name'			=>		Request::get('name'),
			'password'		=>		\Hash::make(preg_replace('/\s+/', '',strtolower(Request::get('password')))),
			'usertype_id'	=>		Request::get('usertype_id'),
		]);

		toastr()->success('New User Created Successfully', config('global.system_name'));
    	return redirect()->back();
	}

	public function edit($id){
		$user = User::find($id);
		return view('admin.users.edit',compact('user'));
	}

	public function update($id){
		$user = User::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:users,name,$user->id,id",
			'password'					=>	'required',
		    'usertype_id'				=>	'required',
		],
		[
		    'name.required'     		=>	'Username Required',
		    'password.required'			=>	'Password Required',
		    'usertype_id.required'		=>	'Please Select Usertype',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$user->update(Request::all());

		toastr()->success('User Updated Successfully', config('global.system_name'));
    	return redirect()->back();
	}

	public function delete($id){
		$user = User::find($id);
		if(Auth::user()->id == $user->id){
			toastr()->error('User Cannot be Deleted', config('global.system_name'));
    		return redirect()->back();
		}else{
			$user->update([
				'status'		=>		'1',
			]);
		}

		toastr()->success('User Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
	}

}
