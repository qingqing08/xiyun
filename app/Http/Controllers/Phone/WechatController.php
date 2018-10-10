<?php

namespace App\Http\Controllers\Phone;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WechatController extends Controller{
    //
    public function getAccessToken(){
        //这里获取accesstoken  请根据自己的程序进行修改
        $token = DB::table('token')->where('id' , 1)->first();
        return $token->access_token;
    }

    /**
     * 获取ticket
     */
    const TICKET_URL = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=';
    public function get_ticket(){
        $tokens = $this->getAccessToken();

        $url = self::TICKET_URL . $tokens;
        $rand = rand(100000 , 999999);
        Session::put('code' , $rand);
        $params = [
            'action_name'    =>  'QR_LIMIT_STR_SCENE',
            'action_info'   =>  [
                'scene' =>  [
                    'scene_str'  =>  'http://www.pengqq.xyz/sm-login-do?code='.$rand.'&openid=',
                ],
            ],
        ];

        $json = json_encode($params,JSON_UNESCAPED_UNICODE);
        return $this->curlPost($url, $json);

    }
    const QRCODE_URL = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=';
    public function qrcode(){
        $ticket_json = $this->get_ticket();

        $ticket_arr = json_decode($ticket_json , true);

        $ticket = urlencode($ticket_arr['ticket']);
        $url = self::QRCODE_URL .$ticket;

        $arr = [
            'url'   =>  $url,
            'code'  =>  Session::get('code'),
        ];

        return json_encode($arr);
//        header("location:".$url);
    }

    /**
     * 通过CURL发送数据
     * @param $url 请求的URL地址
     * @param $data 发送的数据
     * return 请求结果
     */
    protected function curlPost($url,$data){
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
