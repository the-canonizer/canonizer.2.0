<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Session;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {   
        if(Auth::check()){
            if(!session('defaultUserAlgo')){
                session(['defaultUserAlgo'=>Auth::user()->default_algo]);
            }

            if(!session('defaultAlgo')){
                session(['defaultAlgo' => 'blind_popularity']);
            }

            if(session('defaultUserAlgo') != session('defaultAlgo')){
                session(['defaultAlgo' => session('defaultUserAlgo')]);
            }
        }else{
            if(!session('defaultAlgo')){
                session(['defaultAlgo' => 'blind_popularity']);
            }
        }
        

        if(!session('defaultNamespaceId')){
             session(['defaultNamespaceId' => '1']);
        }
       
    }
}
