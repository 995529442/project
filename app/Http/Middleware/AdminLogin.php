<?php

namespace App\Http\Middleware;

use Closure;

class AdminLogin
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
        $check = \Auth::guard('admins')->check();  
        if(!$check){
            if(in_array($request->getRequestUri(),array('/','/Index','/Index/index','/Index/main'))){
               return redirect('login/index2');
            }else{
               return redirect('/html/error.blade.php');
            }              
        }  
        return $next($request);  
    }
}
