<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Validator;

class LoginController extends Controller {

        public function getLogin(){
            return view('admin.login');
        }

         public function postLogin(Request $request){
            
            $data = $request->only(['email','password']);
            
            $validator = Validator::make($data, [
            'email' => 'required',
            'password' => 'required',
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator);
            }

            
            if (Auth::attempt($data))
            {
                return redirect()->intended('/admin');
            }
            return redirect()->back()->withErrors(['password'=>'Credentials not matched']);
        }
}