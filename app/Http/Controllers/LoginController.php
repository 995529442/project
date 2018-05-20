<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gregwar\Captcha\CaptchaBuilder;
use Session;
use Captcha;

class LoginController extends Controller
{
    /**
     * 登录首页
     * @return view
     */
    public function index()
    {
    	return view("login.index");
    }

    /**
     * 检查是否登录
     * @return json
     */
    public function checkLogin(Request $request)
    {
        if ($request->isMethod('post')){

            $username = $request -> input("username","");  //用户名
            $password = $request -> input("password","");  //密码
            $remember = $request -> input("remember",0);   //是否记住我
            $code     = $request -> input("code","");      //验证码

            $rules = [
                'username' => 'required|max:50',
                'password' => 'required|max:100',
                'code'     => 'required',
            ];
            $messages = [
                'username.required' => '用户名不能为空！',
                'username.max' => '用户名长度不能超过50个字符',
                'password.required' => '密码不能为空',
                'password.max' => '密码长度不能超过100个字符',
                'code.required' => '验证码不能为空',
            ];

            $this->validate($request, $rules, $messages);

            if(!Captcha::check($code)){ //验证码
              return back()->withErrors(['验证码错误'])->withInput();
            }
            if (Auth::guard('admins')->attempt(['username' => $username, 'password' => $password,'isvalid'=>true],$remember)) {
                // 认证通过...
                return redirect()->to('/');
            }else{
                return back()->withErrors(['用户名或密码错误'])->withInput();
            }
        }else{
            return redirect()->to('login/index');
        }
    }

    /**
     * 退出登录
     * @return 
     */
    public function logout()
    {
        Auth::guard('admins')->logout();
        
        return redirect()->to('login/index');
    }
    
     /**
     * 验证码
     * @return 
     */ 
    public function captcha()
    {
        return Captcha::create();
        
    }
    
}
