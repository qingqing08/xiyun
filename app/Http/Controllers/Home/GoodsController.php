<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class GoodsController extends Controller{
    //

    public function goods_list(){
        $goods_list = DB::table('goods')->select('id' , 'name' , 'num' , 'in_price' , 'out_price')->paginate(5);
//        foreach ($goods_list as $goods){
//            $typeinfo = DB::table('type')->where('id' , $goods->type_id)->first();
//            $goods->type_id = $typeinfo->name;
//        }
        $count = DB::table('goods')->count();
//        dd($goods_list);
        return view('home.goods.goodslist' , ['title' => '库存列表' , 'goodslist'=>$goods_list , 'count'=>$count]);
    }

    public function goods_add(){
        $typelist = DB::table('type')->get();
        return view('home.goods.goodsadd' , ['title' => '添加库存' , 'typelist'=>$typelist]);
    }

    public function goods_add_do(){
//        $file = $request->file('image');
////        dd($file);die;
//        if ($file->isValid()) {
//
//            // 获取文件相关信息
//            $originalName = $file->getClientOriginalName(); // 文件原名
//            $ext = $file->getClientOriginalExtension();     // 扩展名
//            $realPath = $file->getRealPath();   //临时文件的绝对路径
//            $type = $file->getClientMimeType();     // image/jpeg
//
//            // 上传文件
//            $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
//            // 使用我们新建的uploads本地存储空间（目录）
//            //这里的uploads是配置文件的名称
//            $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
////            var_dump($bool);
//
//        }

        $data = Input::post();
        unset($data['_token']);
        unset($data['file']);
        $data['image_url'] = 'uploads/'.$data['image_url'];
        $data['ctime'] = time();
//        $data = [
//            'name'  =>  Input::post('name'),
//            'num'  =>  Input::post('num'),
//            'type_id'  =>  Input::post('type_id'),
//            'in_price'  =>  Input::post('in_price'),
//            'out_price'  =>  Input::post('out_price'),
//            'image_url' =>  'uploads/'.Input::post('image_url'),
//            'ctime'  =>  time(),
//        ];
//        dd($data);

        $res = DB::table('goods')->insert($data);

        if ($res){
            return ['data'=>'' , 'status'=>1000 , 'message'=>'success'];
        } else {
            return ['data'=>'' , 'status'=>1001 , 'message'=>'error'];
        }
    }

    public function up_img(Request $request){
//        dd($request->file());
        $file = $request->file('file');
//        dd($file);die;
        if ($file->isValid()) {

            // 获取文件相关信息
            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg

            // 上传文件
            $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
            // 使用我们新建的uploads本地存储空间（目录）
            //这里的uploads是配置文件的名称
            $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
//            var_dump($bool);
        }

        return ['data'=>$filename , 'status'=>1000 , 'message'=>'success'];
    }
}
