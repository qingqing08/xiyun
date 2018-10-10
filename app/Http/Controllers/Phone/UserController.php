<?php

namespace App\Http\Controllers\Phone;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\DocBlock\Tags\See;
use Illuminate\Support\Facades\Redis;
//use App\Models\Member;

class UserController extends Controller{

    public function wx_login(){
        $code = $_GET['code'];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx996fa85abda5e676&secret=4fa1f553b231ec2bed06cdc7d3491ae0&code=".$code."&grant_type=authorization_code";
//
        $data = file_get_contents($url);
//
//        echo $data;die;
        $arr = json_decode($data , true);

        $user_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$arr['access_token']."&openid=".$arr['openid']."&lang=zh_CN";

        $user_data = file_get_contents($user_url);
//
        $user_arr = json_decode($user_data , true);
//        $user_arr = [
//            'openid'    =>  "owRHY1cti1oJT7ZfEgzXNbTyJPEo",
//            'nickname'  =>  "晴晴",
//            'sex'   =>  2,
//            'language'  =>  'zh_CN',
//            'city'  =>  '昌平',
//            'province'  =>  '北京',
//            'country'   =>  '中国',
//            'headimgurl'    =>  'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJ8cObich5lCfPGjZNRibJFhnOrazTad5cfKxQOPKSoq6oicXAVLJbwgl6FSaicibcraONsMC1FOW8zAFg/132',
//            'privilege' => Array (),
//        ];

//        print_r($user_arr);die;
        $openid = $user_arr['openid'];
        $userinfo = DB::table('wx')->where('openid' , $openid)->first();

//        print_r($userinfo);die;
        Session::put('openid' , $openid);
        if (empty($userinfo)){
            $wx_arr = [
                'username'   =>  $user_arr['nickname'],
                'openid' =>  $openid,
                'headimgurl'    =>  $user_arr['headimgurl'],
                'status'    =>  0,
                'ctime' =>  time(),
            ];

            $result = DB::table('wx')->insert($wx_arr);
            if ($result){
                return redirect('is_band');
            }
        } else {
            if ($userinfo->status == 0){
                return redirect('is_band');
            } else {
                $user_info = DB::table('user')->where('wx_openid' , $openid)->first();
                $userinfo->id = $user_info->id;
                Session::put('userinfo' , $userinfo);
                return redirect('self');
            }
        }
    }
//    public function
    public function shopcar(){
        return view('phone.shopcar' , ['title'=>'购物车']);
    }

    public function is_band(){
        return view('phone.is_band' , ['title'=>'是否绑定账号']);
    }

    public function band_do(){
        $username = Input::post('username');
        $password = md5(Input::post('password'));

        $userinfo = DB::table('user')->where(['username'=>$username , 'password'=>$password])->first();

        if (empty($userinfo)){
            return ['msg'=>'账号和密码错误' , 'code'=>0];
        } else {
            $openid = Session::get('openid');
            $data = [
                'wx_openid'    =>  $openid,
            ];
//            return ['msg'=>$openid , 'code'=>2];die;
            $result = DB::table('user')->where(['username'=>$username , 'password'=>$password])->update($data);

            if ($result){
                DB::table('wx')->where('openid' , $openid)->update(['status'=>2]);
                return ['msg'=>'绑定成功,您可以用账号或者微信登录' , 'code'=>1];
            } else {
                return ['msg'=>'绑定失败' , 'code'=>2];
            }
        }
    }

    public function kip(){
        $openid = Session::get('openid');
        $wx_user = [
            'wx_openid'    =>  $openid,
        ];

        $wx_data = [
            'status'    =>  1,
        ];
//        echo $openid;die;
        $result = DB::table('user')->insert($wx_user);
        $res = DB::table('wx')->where('openid' , $openid)->update($wx_data);
        $user_info = DB::table('user')->where('wx_openid' , $openid)->first();
        $wx_user = DB::table('wx')->where('openid' , $openid)->first();
//        dd($user_info);

        $wx_user->id = $user_info->id;
        Session::put('userinfo' , $wx_user);

        return redirect('self');

    }

    public function self(){
        $userinfo = Session::get('userinfo');
//        echo $username;die;
        if (empty($userinfo)){
            $username = "未登录";
        } else {
            $username = $userinfo->username;
        }
        if (isset($userinfo->openid)){
            DB::table('wx')->where('openid' , $userinfo->openid)->update(['sm_status'=>0]);
        }
//        Input::get('uid');
        if (!empty(Input::get('userid'))){
//            dd(Input::get());
            Cookie::queue('parentid' , Input::get('userid') , 100);
        }

        return view('phone.self' , ['title'=>'个人中心' , 'username'=>$username]);
    }
    public function register(){
        return view('phone.register' , ['title'=>'注册']);
    }

    public function register_do(){
        //接收到表单提交的username和password
        $username = Input::post('username');
        $password = Input::post('password');
        $phone = Input::post('phone');
        $code = Input::post('code');
        $parent_id = Cookie::get('parentid');
//        dd($parent_id);
        //根据加密
        $token = md5($username.$password).rand('10000000' , '99999999');
        if (empty($username)){
            return ['msg'=>'用户名不能为空' , 'code'=>'0'];
        }
        if (empty($password)){
            return ['msg'=>'密码不能为空' , 'code'=>'0'];
        }
        if (empty($phone)){
            return ['msg'=>'手机号不能为空' , 'code'=>'0'];
        }
        if (empty($code)){
            return ['msg'=>'验证码不能为空' , 'code'=>'0'];
        }
        if ($code != Session::get('code')){
            return ['msg'=>'验证码错误' , 'code'=>'0'];
        }

        $password = md5($password);

        $data = [
            'username'  =>  $username,
            'password'  =>  $password,
            'phone'     =>  $phone,
            'token'     =>  $token,
            'login_status'  =>  0,
        ];

//        dd($data);die;
        $user_info = DB::table('user')->where('username' , $username)->first();
        if (empty($user_info)){
            $result = DB::table('user')->insert($data);
            if ($result){
                $userinfo = DB::table('user')->where(['username'=>$username , 'password'=>$password])->first();
                $level = DB::table('distributor')->where('self_uid' , $parent_id)->first();
                $reg = [
                    'parent_uid'    =>  $parent_id,
                    'self_uid'      =>  $userinfo->id,
                    'level'     =>  $level->level+1,
                    'ctime'     =>  time(),
                ];
                DB::table('distributor')->insert($reg);
                return ['msg'=>'注册成功' , 'code'=>1];
            } else {
                return ['msg'=>'注册失败' , 'code'=>2];
            }
        } else {
            return ['msg'=>'该用户已存在' , 'code'=>0];
        }


    }

    public function login(){
        return view('phone.login' , ['title'=>'登录']);
    }

    public function login_do(){
        //接收到表单提交的username和password
        $username = Input::post('username');
        $password = Input::post('password');
        //根据加密
        $token = md5($username.$password).rand('10000000' , '99999999');
        if (empty($username)){
            return ['msg'=>'用户名不能为空' , 'code'=>'0'];
        }
        if (empty($password)){
            return ['msg'=>'密码不能为空' , 'code'=>'0'];
        }
        $password = md5($password);
        $user_info = DB::table('user')->where(['username'=>$username])->first();
//        dd($user_info);die;
        if (!empty($user_info)){
            if ($user_info->password != $password){
                return ['code'=>0 , 'msg'=>'账号密码不匹配'];
            }
            $data = [
                'token' =>  $token,
                'login_status'  =>  1,
            ];
            $result = DB::table('user')->where('username' , $username)->update($data);
            if ($result){
                $user_info->log_type = 'zh';
                Session::put('userinfo' , $user_info);
                Session::put('token' , $token);
                return ['code'=>1 , 'msg'=>'登录成功'];
            } else {
                return ['code'=>2 , 'msg'=>"登录失败"];
            }
        } else {
            return ['code'=>0 , 'msg'=>'账号密码不匹配'];
        }
    }

    public function reset_password(){
        $username = Session::get('username');
        if (empty($username)){
            return redirect('login');
        }
        $user_info = DB::table('user')->where('username' , $username)->get()->first();
        $data['id'] = $user_info->id;
        return view('phone.reset_password' , ['title'=>'重置密码' , 'data'=>$data]);
    }

    public function reset_do(){
//        dd($data);die;
        $uid = Input::post('uid');
        $password = Input::post('password');
        $data['password'] = md5($password);
        $username = Session::get('username');
        if (empty($uid)){
            return ['msg'=>'用户登录失效' , 'code'=>5];
        }

        if (empty($password)){
            return ['msg'=>'请输入密码' , 'code'=>0];
        }
        $result = DB::table('user')->where(['id'=>$uid , 'username'=>$username])->update($data);

        if ($result){
            return ['msg'=>'修改成功' , 'code'=>1];
        } else {
            return ['msg'=>'修改失败' , 'code'=>2];
        }
    }

    public function logout(){
        Session::flush();
        return redirect('/');
    }

    public function order(){
        $status = Input::get('status');
        $user_info = Session::get('userinfo');
        if (empty($status)){
            $order_list = DB::table('ordercontent')->where('u_id' , $user_info->id)->get();
            $price = 0;
            foreach ($order_list as $order){
                $order->time = date('Y-m-d H:i:s' , $order->time);
                if ($order->status == 1){
                    $order->status = '待支付';
                    $order->status_b = '去支付';
                } else if ($order->status == 2){
                    $order->status = '待发货';
                    $order->status_b = '';
                } else if ($order->status == 3){
                    $order->status = '已发货';
                    $order->status_b = '确认收货';
                } else if ($order->status == 4){
                    $order->status = '已完成';
                    $order->status_b = '确认收货';
                } else {
                    $order->status = '交易关闭';
                    $order->status_b = '';
                }

                $order->order = DB::table('order')->where('order_id' , $order->order_number)->get();
                $price = 0;
                foreach ($order->order as $goods){
                    $info = DB::table('goods')->where('id' , $goods->g_id)->first();
                    $goods->title = $info->name;
                    $goods->image_url = $info->image_url;
                    $num = $goods->price*$goods->num;
//                    $price = $price+$num;
                }
                $order->sum_price = $num;
            }
//            dd($order_list);

            return view('phone.order' , ['order_list'=>$order_list]);
        }
    }

    public function get_sms(){
        date_default_timezone_set("PRC");
        $tel = Input::post('phone');
        if (empty($tel)){
            return ['msg'=>'手机号不能为空' , 'code'=>0];
        }

        $rand = rand(100000 , 999999);
        $showapi_appid = '69664';  //替换此值,在官网的"我的应用"中找到相关值
        $showapi_secret = '007c22caa1f94c1996c5fcf637f1290f';  //替换此值,在官网的"我的应用"中找到相关值
        $paramArr = array(
            'showapi_appid'=> $showapi_appid,
            'mobile'=> "$tel",
            'content'=> "{\"name\":\"$tel\",\"code\":\"$rand\",\"minute\":\"5\"}",
            'tNum'=> "T170317002781",
            'title'=>'希芸护肤',
            'big_msg'=> ""
            //添加其他参数
        );

        $param = $this->createParam($paramArr,$showapi_secret);
        $url = 'http://route.showapi.com/28-1?'.$param;
//        echo "请求的url:".$url."\r\n";
        $result = file_get_contents($url);
//        echo "返回的json数据:\r\n";
//        print $result.'\r\n';
        $result = json_decode($result , true);
//        echo "\r\n取出showapi_res_code的值:\r\n";
        if ($result['showapi_res_body']['remark'] == "提交成功!" && $result['showapi_res_body']['successCounts'] == 1){
            $data = [
                'phone' =>  $tel,
                'code'  =>  $rand,
                'ctime' =>  time(),
            ];
            $code_info = DB::table('getcode')->where('phone' , $tel)->first();
//            $code_info = $code_info->toArray();
            if (empty($code_info)){
                $res = DB::table('getcode')->insert($data);
            } else {
                $res = DB::table('getcode')->where('phone' , $tel)->update($data);
            }

            if ($res){
                Session::put('code' , $rand);
                return ['msg'=>'短信发送成功' , 'code'=>1];
            } else {
                return ['msg'=>'短信发送失败' , 'code'=>2];
            }
        } else {
            return ['msg'=>'短信发送失败' , 'code'=>2];
        }
//        dd($result);
//        echo "\r\n";
    }

    public function createParam ($paramArr,$showapi_secret) {
        $paraStr = "";
        $signStr = "";
        ksort($paramArr);
        foreach ($paramArr as $key => $val) {
            if ($key != '' && $val != '') {
                $signStr .= $key.$val;
                $paraStr .= $key.'='.urlencode($val).'&';
            }
        }
        $signStr .= $showapi_secret;//排好序的参数加上secret,进行md5
        $sign = strtolower(md5($signStr));
        $paraStr .= 'showapi_sign='.$sign;//将md5后的值作为参数,便于服务器的效验
//        echo "排好序的参数:".$signStr."\r\n";
        return $paraStr;
    }

    public function personal(){
        $userinfo = Session::get('userinfo');
//        echo $username;die;
        if (empty($userinfo)){
            return redirect('login');
        }
        return view('phone.personal' , ['title'=>'个人中心' , 'userinfo'=>$userinfo]);
    }

    public function sm_login(){
        $src = file_get_contents("http://www.pengqq.xyz/qrcode");

        $arr = json_decode($src , true);
//        dd($arr);

        return view('phone.sm_login' , ['title'=>'扫码登录','url'=>$arr['url'] , 'code'=>$arr['code']]);
    }

    public function is_login(){
        $code = Input::post('code');
//        echo $code;
        $arr = DB::table('wx')->where('code' , $code)->first();
//        dd($arr);
        if (empty($arr)){
            $status = Session::get('status');
//            echo $status;die;
            if ($status == 3){
                return ['msg'=>'扫描成功,请确认' , 'code'=>2];
            } elseif($status == 4){
                return ['msg'=>'已取消' , 'code'=>2];
            } else {
                return ['code'=>2 , 'msg'=>'请使用手机微信扫一扫登录'];
            }
        } else {
            if ($arr->status == 0){
                Session::put('openid' , $arr->openid);
                return ['code'=>2 , 'msg'=>'是否绑定账号(<a href="kip">跳过</a> | <a href="is-band">绑定</a>)'];
            } else {
                $user = DB::table('user')->where('wx_openid' , $arr->openid)->first();
                $arr->id = $user->id;
                Session::put('userinfo' , $arr);
                DB::table('wx')->where('openid' , $arr->openid)->update(['sm_status'=>1]);
                return ['code'=>1 , 'msg'=>'登录成功'];
            }
//            if ($arr->sm_status == 0){
//                if ($arr->status == 0){
//                    Session::put('openid' , $arr->openid);
//                    return ['code'=>2 , 'msg'=>'是否绑定账号(<a href="kip">跳过</a> | <a href="is-band">绑定</a>)'];
//                } else {
//                    $user = DB::table('user')->where('wx_openid' , $arr->openid)->first();
//                    $arr->id = $user->id;
//                    Session::put('userinfo' , $arr);
//                    DB::table('wx')->where('openid' , $arr->openid)->update(['sm_status'=>1]);
//                    return ['code'=>1 , 'msg'=>'登录成功'];
//                }
//            } else {
//                return ['code'=>2 , 'msg'=>'验证码已过期,点击刷新(<a href="sm_login">刷新</a>)'];
//            }

        }

    }

    public function cancel(){
        Session::put('status' , 4);
        echo "您取消了微信登录";
    }

    public function login_view(){
        $openid = Input::get('openid');
        $code = Input::get('code');

//        Redis::set('name', 'guwenjie');
//        $values = Redis::get('name');
//        dd($values);

        return view('phone.login_view' , ['openid'=>$openid,'code'=>$code]);
    }

    public function sm_login_do(){
        $code = Input::get('code');
        if (empty(Input::get('openid'))){
            $openid = $this->get($code);
        } else {
            $openid = Input::get('openid');
        }

        $wx_info = DB::table('wx')->where('openid' , $openid)->first();

        if (empty($wx_info)){
            $arr = [
                'openid'    =>  $openid,
                'username'  =>  "微信".rand('100000' , '999999'),
                'headimgurl'    =>  '',
                'status'    =>  0,
                'code'      =>  $code,
                'ctime' =>  time(),
            ];

            $result = DB::table('wx')->insert($arr);

        } else {
            $result = DB::table('wx')->where('openid' , $openid)->update(['code'=>$code]);
        }
        if ($result){
            $wx_info = DB::table('wx')->where('openid' , $openid)->first();
            $user_info = DB::table('user')->where('wx_openid' , $openid)->first();

            $wx_info->id = $user_info->id;

            Session::put('userinfo' , $wx_info);
            echo "登录成功";
        } else {
            echo "登录失败";
        }
    }

    public function get(){
        $code = Input::get('code');
        Session::put('code' , $code);
        header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx996fa85abda5e676&redirect_uri=http://www.pengqq.xyz/get-openid?response_type=code&scope=snsapi_userinfo&state=STATEA#wechat_redirect");
//        echo "<script>window.location.href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx996fa85abda5e676&redirect_uri=http://www.pengqq.xyz/get-openid?response_type=code&scope=snsapi_userinfo&state=STATEA#wechat_redirect';</script>";
    }

    public function get_openid(){
        $code = $_GET['code'];
        dd(Input::get());
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx996fa85abda5e676&secret=4fa1f553b231ec2bed06cdc7d3491ae0&code=".$code."&grant_type=authorization_code";
//
        $data = file_get_contents($url);
//
//        echo $data;die;
        $arr = json_decode($data , true);


        $user_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$arr['access_token']."&openid=".$arr['openid']."&lang=zh_CN";

        $user_data = file_get_contents($user_url);
//
        $user_arr = json_decode($user_data , true);
//        $user_arr = [
//            'openid'    =>  "owRHY1cti1oJT7ZfEgzXNbTyJPEo",
//            'nickname'  =>  "晴晴",
//            'sex'   =>  2,
//            'language'  =>  'zh_CN',
//            'city'  =>  '昌平',
//            'province'  =>  '北京',
//            'country'   =>  '中国',
//            'headimgurl'    =>  'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJ8cObich5lCfPGjZNRibJFhnOrazTad5cfKxQOPKSoq6oicXAVLJbwgl6FSaicibcraONsMC1FOW8zAFg/132',
//            'privilege' => Array (),
//        ];

//        print_r($user_arr);die;
        $openid = $user_arr['openid'];
        $code = Session::get('code');
        Session::put('code' , '');
        Session::put('openid' , $openid);
        $wx_info = DB::table('wx')->where('openid' , $openid)->first();

        if (empty($wx_info)){
            $arr = [
                'openid'    =>  $openid,
                'username'  =>  $user_arr['nickname'],
                'headimgurl'    =>  $user_arr['headimgurl'],
                'status'    =>  0,
                'code'      =>  $code,
                'ctime' =>  time(),
            ];

            $result = DB::table('wx')->insert($arr);
            if ($result){
                echo "登录成功";
            } else {
                echo "登录失败";
            }
        }
//        header("location:http://www.pengqq.xyz/sm-login-do?code=".$code.'&openid='.$openid);
//        return redirect('sm-login-do?code='.$code.'&openid='.$openid);
//        echo $openid;
    }
}
