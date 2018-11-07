<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller{
    //


    public function index(){
        $list = DB::table('rule')->where(['parent_id'=>0 , 'status'=>1])->get();
        $list = json_decode($list);
        foreach ($list as $key => $value) {
            $data = DB::table('rule')->where(['parent_id'=>$value->id , 'status'=>1])->get();
            $value->child = $data;
        }

        $userinfo = Session::get('userinfo');
//        dd($userinfo);
        return view('home.index' , ['title'=>'后台首页' , 'list'=>$list , 'userinfo'=>$userinfo]);
    }

    public function welcome(){
        $h = date('H');
        if ($h > 0 && $h < 3){
            $content = '夜深了,忙碌了一天让自己好好休息吧！';
        } elseif ($h > 3 && $h < 5){
            $content = '已经很晚了,不要让自己变成熊猫眼哦！';
        } elseif ($h > 5 && $h < 7){
            $content = '早上好！';
        } elseif ($h > 7 && $h < 12){
            $content = '上午好！';
        } elseif ($h > 12 && $h < 13){
            $content = '中午好！';
        } elseif ($h > 13 && $h < 17){
            $content = '下午好！';
        } elseif ($h > 17 && $h < 19){
            $content = '傍晚好！';
        } else {
            $content = '晚上好！';
        }

        $userinfo = Session::get('userinfo');
        $level = DB::table('admin')->where('uid' , $userinfo->parent_id)->first();
        if ($level->level == 1){
            $level_name = '战略';
        } elseif ($level->level == 2){
            $level_name = '总代';
        } elseif ($level->level == 3){
            $level_name = '一级';
        } elseif ($level->level == 4){
            $level_name = '二级';
        } else {
            $level_name = '特约';
        }

        $num = DB::table('admin')->where('parent_id' , $userinfo->uid)->count();

        $one = DB::table('admin')->where(['parent_id'=>$userinfo->uid , 'level'=>3])->count();
        $two = DB::table('admin')->where(['parent_id'=>$userinfo->uid , 'level'=>4])->count();
        $teyue = DB::table('admin')->where(['parent_id'=>$userinfo->uid , 'level'=>5])->count();

        $count = [
            'count' =>  $num,
            'one'   =>  $one,
            'two'   =>  $two,
            'teyue' =>  $teyue,
        ];
        return view('home.welcome' , ['count'=>$count , 'content'=>$content , 'userinfo'=>$userinfo , 'level'=>$level->username."-".$level_name]);
    }

    public function statistics(){
        $start = strtotime(date("Y-m-d") , time());
        $end = $start+60*60*24;

        $count = 0;
        $phone_count = 0;
        $web_count = 0;

        $order_list = DB::table('ordercontent')->whereBetween('time' , [$start,$end])->get();
        $order_list = $order_list->toArray();
        if (!empty($order_list)){
            $count = count($order_list);
            foreach ($order_list as $value){
                if ($value->is_mobile == 1){
                    $phone_count++;
                } else {
                    $web_count++;
                }
            }
            $rs['sum'] = $count; //总数
            $rs['phone_count'] = $phone_count; //单个数
            $rs['web_count'] = $web_count; //单个数
            $phone_count = round($rs['phone_count']/$rs['sum']*100,2);
            $web_count = round($rs['web_count']/$rs['sum']*100,2);

        }

        $date = date('Y-m-d' , $start);
        $data = [
            'date'  =>  date('Y-m-d' , $start),
            'count' =>  $count,
            'phone_count'   =>  $phone_count."%",
            'web_count' =>  $web_count.'%',
            'time'      =>  time(),
        ];

        $info = DB::table('count')->where('date' , $date)->first();
        if (empty($info)){
            $result = DB::table('count')->insert($data);
        } else {
            $result = DB::table('count')->where('date' , $date)->update($data);
        }
        echo $date."<br>";
        echo "手机".$phone_count."%<br>";
        echo "电脑".$web_count."%";
    }

    public function log(){
        $filename = "./access.log";

        $data = file_get_contents($filename);
//        dd($data);
//        echo $data;die;
        $type = ['Windows NT 10.0' , 'iPhone' , 'Linux'];

        $preg_server = '/\(.+;/US';
        $preg_date = '/\[.+]/U';
        $preg_all = '/(\[.+])|(\(.+;)/U';
        preg_match_all($preg_server , $data , $server);
        preg_match_all($preg_date , $data , $date);
        preg_match_all($preg_all , $data ,$all);

//        foreach ($server as $value){
//            $arr = $value;
//        }
//        $iphone = 0;
//        $web = 0;
//        for ($i=0;$i<count($arr);$i++){
//            $arr[$i] = trim($arr[$i] , '(');
//            $arr[$i] = trim($arr[$i] , ';');
//            echo $arr[$i];
//            if ($arr[$i] == 'iPhone' || $arr[$i] == 'Linux'){
//                $iphone++;
//            }
//            if ($arr[$i] == 'Windows NT 10.0' || $arr[$i] == 'X11'){
//                $web++;
//            }
//        }
//        echo count($arr)."<br>";
//        echo $iphone."<br>";
//        echo $web."<br>";
        $new_arr = array_merge($server , $date);
        dd($all);
        echo $data;
    }
}
