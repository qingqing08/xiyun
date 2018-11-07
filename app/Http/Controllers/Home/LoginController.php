<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class LoginController extends Controller{
    //
    public function login(){
        return view('home.login' , ['title'=>'后台登陆']);
    }

    public function login_do(){
        $data = Input::post();

        $userinfo = DB::table('admin')->where(['username'=>$data['username'] , 'password'=>md5($data['password'])])->first();
        if (empty($userinfo)){
            return ['code'=>0 , 'msg'=>'账号或密码错误'];
        } else {
            Session::put('userinfo' , $userinfo);
            return ['code'=>1 , 'msg'=>'登录成功'];
        }
    }

    public function register(){
        return view('home.register' , ['title'=>'注册申请']);
    }

    public function register_do(){
        $data = Input::post();

        unset($data['_token']);
        $data['password'] = md5($data['password']);
        $data['ctime'] = time();
        $result = DB::table('admin')->insertGetId($data);

        $userinfo = DB::table('admin')->where('uid' , $result)->first();
        Session::put('userinfo' , $userinfo);
        if ($result){
            return ['code'=>1 , 'msg'=>'注册成功'];
        } else {
            return ['code'=>2 , 'msg'=>'注册失败'];
        }
    }

}
