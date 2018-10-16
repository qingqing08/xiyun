<?php

namespace App\Http\Controllers\Phone;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class Login extends Controller{
    public function login(){
        echo "<a target='_blank' href='http://api.jinxiaofei.xyz/oauth2/authorize?client_id=895170526&redirect_uri=www.pengpeng.com/api-login&response_type=code'>第三方登录</a>";
    }
    //
    public function api_login(){
        $code = Input::get('code');

        $url = "http://api.jinxiaofei.xyz/oauth2/access_token?client_id=895170526&code=".$code;

        $res = file_get_contents($url);
//        $res = fopen($url, 'r');
//        stream_get_meta_data($res);
//        $result = '';
//        while(!feof($res))
//        {
//            $result .= fgets($res, 1024);
//        }
//        echo "url body: $result";
//        fclose($res);



        print_r($res);die;
//        $res = '{"status":"100001","massage":"\u83b7\u53d6\u6210\u529f","data":{"access_token":"2.00f793db70cbf313a6fa06fa330d01a151"}}';
        $arr = json_decode($res , true);

        $access_token = $arr['data']['access_token'];

        $acc_url = 'http://www.api-qq.com/oauth2/get-userinfo?access_token='.$access_token;
//        echo $acc_url;die;
        $data = file_get_contents($acc_url);

        echo $data;


    }

    /**
     * 通过CURL发送数据
     * @param $url 请求的URL地址
     * @param $data 发送的数据
     * return 请求结果
     */
    public function curlPost($url,$data){
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;    //请求url地址
        $params[CURLOPT_HEADER] = FALSE; //是否返回响应头信息
        $params[CURLOPT_SSL_VERIFYPEER] = false;
        $params[CURLOPT_SSL_VERIFYHOST] = false;
        $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
        $params[CURLOPT_POST] = true;
        $params[CURLOPT_POSTFIELDS] = $data;
        curl_setopt_array($ch, $params); //传入curl参数
        $content = curl_exec($ch); //执行
        curl_close($ch); //关闭连接
        return $content;
    }
}
