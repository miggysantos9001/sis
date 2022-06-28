<?php

namespace App\Http\Controllers;

use Request;
use Auth;
use Validator;

class LoginController extends Controller
{
    public function index(){
    	return view('login');
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'			=>	'required',
		    'password'		=>	'required',
		],
		[
		    'name.required' 		=>	'Username Required',
		    'password.required' 	=>	'Password Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		if (Auth::attempt(['name' => Request::input('name'), 'password' => Request::input('password')])) {
            return redirect()->route('dash');
		}else{
            toastr()->error('You are not registered user', config('global.system_name'));
            return redirect()->back();
        }
    }

    public function logout(){
    	Auth::logout();
    	return redirect('/');
    }
}
