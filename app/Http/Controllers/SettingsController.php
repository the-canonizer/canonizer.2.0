<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Model\Camp;
use App\User;
use Illuminate\Support\Facades\Session;
use App\Library\General;
use App\Model\Nickname;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    

    public function index(){
         $user = User::find(Auth::user()->id);
        return view('settings.index',['user'=>$user]);
    }

    public function profile_update(Request $request){
        $input = $request->all();
        $id = (isset($_GET['id'])) ? $_GET['id'] : '';
        if($id){
            $user = User::find($id);
            $user->first_name = $input['first_name'];
            $user->last_name = $input['last_name'];
            $user->middle_name = $input['middle_name'];
            $user->gender = $input['gender'];
            $user->birthday = date('Y-m-d',strtotime($input['birthday']));
            $user->language = $input['language'];
            $user->address_1 = $input['address_1'];
            $user->address_2 = $input['address_2'];
            $user->city = $input['city'];
            $user->state = $input['state'];
            $user->country = $input['country'];
            $user->postal_code = $input['postal_code'];

            $user->update();
            Session::flash('success', "Profile updated successfully.");
            return redirect()->back();
        }
    }


    public function nickname(){
        $id = Auth::user()->id; 
        $encode = General::canon_encode($id);

        $user = User::find(Auth::user()->id);

        //get nicknames
        $nicknames = Nickname::where('owner_code','=',$encode)->get();
        return view('settings.nickname',['nicknames'=>$nicknames,'user'=>$user]);
    }

    public function add_nickname(Request $request){
        $id = Auth::user()->id;
        if($id){
            $messages = [
                'private.required' => 'Visibility status is required.',
            ];
            

            $validator = Validator::make($request->all(), [
                'nick_name' => 'required',
                'private' => 'required',
            ],$messages);
    
            if ($validator->fails()) {
                return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
            }


            $input = $request->all();
            $nickname = new Nickname();
            $nickname->owner_code = General::canon_encode($id);
            $nickname->nick_name = $input['nick_name'];
            $nickname->private = $input['private'];
            $nickname->create_time = time();
            $nickname->save();

            Session::flash('success', "Nick name created successfully.");
        return redirect()->back();
        }else{
            return redirect()->route('login');
        }


    }
}
