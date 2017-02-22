<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\UserCheck;

class LoginCheck
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
        $token = UserCheck::check();
        if($token == ''){
            return redirect('/');
        }
//        return redirect($token);
//        if(UserCheck::check()){
//            return redirect('/home');
//        }else{
//            return redirect('/');
//        }

        return $next($request);
    }
}
