<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class registerController extends Controller
{
    public function register(){
		return view('register',[
			'title'=>'Mexico - Register',
			'page' => 'register',
		]);
	}

	public function store(Request $request){
		$validData = $request->validate([
			'name'=>'required|min:3|max:40',
			'email'=>'required|unique:users|email:dns',
			'password'=>'required|min:6|max:12',
			'phone_number'=>'required|regex:/^08\d{6,10}$/'
		]);

		$validData['password'] = bcrypt($validData['password']);

		User::create($validData);

		return redirect('/login');
	}
}
