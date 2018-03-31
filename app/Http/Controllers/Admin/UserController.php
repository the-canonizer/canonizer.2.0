<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller {

    public function getIndex(Request $request){
        $users = User::paginate(10);
        return  view('admin.users',compact('users'));
    }

    public function getEdit(Request $request,$id){
        $user = User::find($id);
        if(!$user){
            return redirect('/admin/users');
        }
        return view('admin.user-edit',compact('user'));
    }

    public function postUpdate(Request $request,$id){
        $data = $request->only(['first_name','middle_name','last_name','address_1','address_2','city','state','postal_code','country']);

        User::where('id',$id)->update($data);
        return redirect('/admin/users');
    }
}