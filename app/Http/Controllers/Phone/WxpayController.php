<?php

namespace App\Http\Controllers\Phone;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use JSSDK;
use QRcode;

class WxpayController extends Controller{
    //扫一扫

    public function scan_goods(){
        $appid = "wx996fa85abda5e676";
        $appSecret = "4fa1f553b231ec2bed06cdc7d3491ae0";
        $jssdk = new JSSDK($appid, $appSecret);
//        dd($jssdk);
        //返回签名基本信息
        $signPackage = $jssdk->getSignPackage();
//        dd($signPackage);

        $goods_list = DB::table('wx_shopcar')->get();

        $sum_price = 0;
        foreach ($goods_list as $val){
            $goods_info = DB::table('goods')->where('id' , $val->goods_id)->first();
            $val->title = $goods_info->name;
            $val->image_url = $goods_info->image_url;
            $val->price = $goods_info->in_price;
            $sum_price += $goods_info->in_price*$val->num;
//            $sum_price += $goods_info->in_price*$val->num;
//            $val->sum_price = $sum_price;
        }

//        dd($goods_list);
        return view('phone.wxpay.scan' , ['title'=>"结算" ,'signPackage'=>$signPackage , 'goods_list'=>$goods_list , 'sum_price'=>$sum_price]);
    }

    public function goods_qrcode(){
        $qrcode = new QRcode();

        $text = "3";//要生成二维码的文本

        $qrcode->png($text,"./code.png",'H',10,1,false);//输出到浏览器或者生成文件

//        return view('');
//        echo json_encode($arr);
    }

    public function wx_buy(){
        $goods_id = Input::get("goodsid");
        $openid = "owRHY1cti1oJT7ZfEgzXNbTyJPEo";

        $data = [
            'goods_id'  =>  $goods_id,
            'wx_openid' =>  $openid,
            'num'       =>  1,
            'ctime'     =>  time(),
        ];

        $info = DB::table('wx_shopcar')->where(['wx_openid'=>$openid , 'goods_id'=>$goods_id])->first();
        if (empty($info)){
            $result = DB::table("wx_shopcar")->insert($data);
        } else {
            $result = DB::table('wx_shopcar')->where(['wx_openid'=>$openid , 'goods_id'=>$goods_id])->update(['num'=>$info->num+1]);
        }
//
//        if ($result){
//            echo "登录成功";
//        }

    }
}
