<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TypeController extends Controller{
    //
    public function typelist(){
        $typelist = DB::table('type')->get();
        foreach ($typelist as $k=>$v){
            $v->ctime = date("Y-m-d H:i:s" , $v->ctime);
        }
        return view('home.type.list' , ['typelist' => $typelist , 'title' => '类型列表']);
    }

    public function typeadd(){
        return view('home.type.add' , ['title'=>'添加类别']);
    }

    public function typeadd_do(){
//        dd(input::post());die;
        $data = input::post();
        unset($data['_token']);
        $data['ctime'] = time();
        $result = DB::table('type')->insert($data);
        if ($result){
            return redirect("list");
        } else {
            return redirect("list");
        }
    }
}
