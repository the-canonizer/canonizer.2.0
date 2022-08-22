<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        //If the status is not approved redirect to login 
        if(Auth::check() && Auth::user()->status ==0 ){
            Auth::logout();
            // return redirect('/login')->with('erro_login', 'User is no longer active on canonizer');
            return redirect('/login')->with('erro_login', 'Your account is not verified yet. You must have received the verification code in your registered email or mobile. If not then you can request for new code by clicking on the button below.');
        }
        if(Auth::check() && Auth::user()->is_active ==0 ){
            Auth::logout();
            // return redirect('/login')->with('erro_login', 'User is no longer active on canonizer');
            return redirect('/login')->with('erro_login', 'Your account has been suspended temporarily. Please contact support@canonizer.com for further assistance.');
        }
        return $response;
    }
}