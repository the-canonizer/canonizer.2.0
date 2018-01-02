<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Model\Camp;
use App\User;

class SettingsController extends Controller
{
    

     public function index(){
         $user = User::find(Auth::user()->id);
        return view('settings.index',['user'=>$user]);
     }


    public function nickname(){
       
        return view('settings.nickname');
    }
}
