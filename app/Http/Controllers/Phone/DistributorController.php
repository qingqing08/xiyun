<?php

namespace App\Http\Controllers\Phone;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use JSSDK;

class DistributorController extends Controller{
    //
    public function distributor(){
        $user_info = Session::get('userinfo');
//        dd($user_info);

        $dis = DB::table("distributor")->where('self_uid' , $user_info->id)->first();

        if (empty($dis)){
            $arr = [
                'parent_uid' =>  '',
                'self_uid'   =>  $user_info->id,
                'level' =>  1,
                'ctime' =>  time(),
            ];

            DB::table("distributor")->insert($arr);
        }

        $info = DB::table("distributor")->where('self_uid' , $user_info->id)->first();
        return view('phone.distributor.info' , ['title'=>'分销','info'=>$info]);
    }

    public function test(){
        $appid = "wx996fa85abda5e676";
        $appSecret = "4fa1f553b231ec2bed06cdc7d3491ae0";
        $jssdk = new JSSDK($appid, $appSecret);
//        dd($jssdk);
        //返回签名基本信息
        $signPackage = $jssdk->getSignPackage();
//        dd($signPackage);

        $user_info = Session::get('userinfo');
//        dd($user_info);

        $user_info = array(
            "openid" => "owRHY1cti1oJT7ZfEgzXNbTyJPEo",
            "username" => "晴晴",
            "headimgurl" => "http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJ8cObich5lCfPGjZNRibJFhnOrazTad5cfKxQOPKSoq6oicXAVLJbwgl6FSaicibcraONsMC1FOW8zAFg/132",
            "status" => 2,
            "code" => "578315",
            "sm_status" =>  0,
            "ctime" => null,
            "id" => 2,
        );
        return view('phone.distributor.test' , ['title'=>'分销' , 'user_info'=>$user_info , 'signPackage'=>$signPackage , 'webhost'=>"http://".$_SERVER['HTTP_HOST']]);
    }

}
