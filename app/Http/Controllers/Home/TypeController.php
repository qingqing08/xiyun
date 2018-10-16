<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TypeController extends Controller{
    //
    public function typelist(){
        $typelist = DB::table('type')->paginate(2);
        foreach ($typelist as $k=>$v){
            $v->ctime = date("Y-m-d H:i:s" , $v->ctime);
        }
        $count = DB::table('type')->count();
//        dd($typelist);
        return view('home.type.list' , ['count' => $count , 'typelist' => $typelist , 'title' => '类型列表']);
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
