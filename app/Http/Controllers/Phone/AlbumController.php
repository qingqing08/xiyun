<?php

namespace App\Http\Controllers\Phone;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use JSSDK;

class AlbumController extends Controller{
    //
    public function set_redis(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1' , 6379);

        $openid = Input::get('openid');
        $redis->set('wx_openid' , $openid);

//        echo $redis->get("wx_openid");
    }

    public function album_list(){
        $appid = "wx996fa85abda5e676";
        $appSecret = "4fa1f553b231ec2bed06cdc7d3491ae0";
        $jssdk = new JSSDK($appid, $appSecret);
//        dd($jssdk);
        //返回签名基本信息
        $signPackage = $jssdk->getSignPackage();
//        dd($signPackage);

        $redis = new \Redis();
        $redis->connect('127.0.0.1' , 6379);
//        echo $redis->get("wx_openid");die;

        $openid = $redis->get('wx_openid');
        $album_list = DB::table("album")->where(["wx_openid"=>$openid , "is_del"=>1])->get();

        $album_list = json_decode($album_list , true);
//        dd($album_list);

        return view('phone.album.list' , ["openid"=>$openid , "album_list"=>$album_list , 'signPackage'=>$signPackage]);
    }

    public function create_album(){
        $openid = Input::post("openid");
//        $album_list = DB::table("album")->where("wx_openid" , $openid)->get();
//
//        $album_list = json_decoded($album_list , true);
        $data = [
            'wx_openid' =>  $openid,
            'album_name'    =>  '新相册',
            'is_photo'  =>  0,
            'is_del'    =>  1,
            'ctime'     =>  time(),
        ];

        $result = DB::table('album')->insert($data);

        if ($result){
            return ['code'=>1 , 'msg'=>'创建成功'];
        } else {
            return ['code'=>0 , 'msg'=>'创建失败'];
        }
    }

    public function up_name(){
        $album_id = Input::post("album_id");
        $album_name = Input::post("album_name");

        $album_info = DB::table("album")->where("id" , $album_id)->first();
//        $album_info = json_decode($album_info , true);

        if (empty($album_info)){
            return ['code'=>2 , 'msg'=>'非法操作'];
        }

        if ($album_name != $album_info->album_name){
            $data = [
                'album_name'    =>  $album_name,
            ];

            $result = DB::table("album")->where('id' , $album_id)->update($data);
        }

        return ['code'=>1 , 'msg'=>'修改成功' , 'album_name'=>$album_name];

    }

    public function album_view(){
        $album_id = Input::get('album_id');

        $album_info = DB::table("album")->where('id' , $album_id)->first();

        if (empty($album_info)){
            echo "非法操作";
        }

        if ($album_info->is_photo == 0){
            $photo_list = [];
        } else {
            $photo_list = DB::table("photo")->where("album_id" , $album_id)->get();
            $photo_list = json_decode($photo_list , true);
        }

//        dd($photo_list);
        $appid = "wx996fa85abda5e676";
        $appSecret = "4fa1f553b231ec2bed06cdc7d3491ae0";
        $jssdk = new JSSDK($appid, $appSecret);
//        dd($jssdk);
        //返回签名基本信息
        $signPackage = $jssdk->getSignPackage();

        return view("phone.album.photolist" , ["album_id"=>$album_id , 'photo_list'=>$photo_list , 'signPackage'=>$signPackage]);
    }

    public function get_photo(){
        $album_id = Input::post("album_id");
        $serverid = Input::post("serverId");
        $redis = new \Redis();
        $redis->connect('127.0.0.1' , 6379);
        $access_token = $redis->get("wx_access_token");
//        echo $access_token;die;
//        file_put_contents('photo.log' , print_r(Input::post() , true) , FILE_APPEND);
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$serverid";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $media = array_merge(array('mediaBody' => $package), $httpinfo);

        //求出文件格式
        preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
        $fileExt = $extmatches[1];
        $filename = time().rand(100,999).".{$fileExt}";
        $dir = "/home/wwwroot/xiyun/public";
        $dirname = "/uploads/photo/";
        $local_file = fopen($dir.$dirname.$filename,'w');
        if($local_file !== false){
            if(fwrite($local_file,$media['mediaBody']) !== false){
                fclose($local_file);
            }
        }
        $src=$dirname.$filename;

        $data = [
            'album_id'  =>  $album_id,
            'url'   =>  $src,
            'ctime' =>  time(),
        ];

        $result = DB::table("photo")->insert($data);

        DB::table('album')->where('id' , $album_id)->update(['is_photo'=>1]);
        return ['msg'=>"上传成功"];
//        echo $src;
//        file_get_contents($url);

//        print_r($data);
    }
}
