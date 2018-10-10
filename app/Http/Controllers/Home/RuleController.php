<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class RuleController extends Controller{
    //
    public function list(){
        $list = DB::table('rule')->where('status',1)->get();
//        dd($list);
        foreach ($list as $key => $value) {
            if ($value->parent_id != 0) {
                $parent = DB::table('rule')->where('id' , $value->parent_id)->first();
                $value->parent_id = $parent->rule_name;
            } else {
                $value->parent_id = "一级权限";
            }
        }
        // dd($list);
        return view('home.rule.list' , ['title'=>'权限列表' , 'rule_list'=>$list]);
    }

    public function add(){
        $rule_list = DB::table('rule')->get();

        return view('home.rule.add' , ['title'=>'添加权限' , 'rule_list'=>$rule_list]);
    }

    public function add_do(){
        $data = Input::post();
        // dd($data);
        unset($data['_token']);
        $data['status'] = 1;
        $result = DB::table('rule')->insert($data);
        if ($result) {
            return ['code'=>1 , 'msg'=>'添加成功'];
        } else {
            return ['code'=>2 , 'msg'=>'添加失败'];
        }
    }
}
