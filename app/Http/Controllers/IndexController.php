<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{   
    /**
     * 后台首页
     * @return view
     */
    public function index()
    {
    	$admins = Auth::guard("admins")->user();

        $username = $admins->username;  //用户名

        return view("index",[
            'username' => $username
        ]);
    }
    
    /**
     * 后台右边主页
     * @return view
     */
    public function main()
    {
        return view("main");
    }
}
