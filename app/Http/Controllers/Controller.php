<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Session;
use Auth;
use Cookie;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {   
        //dd(Auth::user());
		
        /*@session_start();
        $_SESSION['defaultAlgo'] = 'blind_popularity';
        dd($_SESSION['defaultAlgo']);*/

        /*Cookie::queue('defaultAlgo','blind_popularity', 2);
        dd(Cookie::get('defaultAlgo'));
        if(Auth::check()){
           dd(session('defaultUserAlgo'));
            if(!session('defaultUserAlgo')){
                session(['defaultUserAlgo'=>Auth::user()->default_algo]);
                session(['defaultAlgo' => session('defaultUserAlgo')]);
            }

            if(!session('defaultAlgo')){
                session(['defaultAlgo' => 'blind_popularity']);
            }
            
        }else{
            if(!session('defaultAlgo')){
                session(['defaultAlgo' => 'blind_popularity']);
            }
        }
        
        
        if(!session('defaultNamespaceId')){
             session(['defaultNamespaceId' => '1']);
        }*/
       
    }
}
