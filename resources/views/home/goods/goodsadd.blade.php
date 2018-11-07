@extends('layouts.homeheader')
<script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/layui/layui.js"></script>
<body>
<div class="x-body">
    <form class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">商品类别</label>
            <div class="layui-input-inline">
                <select name="type_id" id="type_id" lay-verify="required">
                    <option value="0">请选择</option>
                    @foreach($typelist as $type)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @csrf
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>产品名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="name" name="name" required="" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>库存
            </label>
            <div class="layui-input-inline">
                <input type="number" id="num" name="num" required="" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>进价
            </label>
            <div class="layui-input-inline">
                <input type="number" id="in_price" name="in_price" required="" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>出货价
            </label>
            <div class="layui-input-inline">
                <input type="number" id="out_price" name="out_price" required="" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">封面图</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="image_url" name="file">
                    <i class="layui-icon">&#xe67c;</i>上传图片
                </button>
                <input name="image_url" id="file_path"  />
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="add" lay-submit="">
                增加
            </button>
        </div>
    </form>
</div>

<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //监听提交
        form.on('submit(add)', function(data){
            var rule_name = $("input[name=name]").val();
            $.ajax({
                url:"goods-add-do",
                type:"post",
                // dataType:"json",
                data:data.field,
                cache:false,
                async:false,
                success:function (data){
                    if (data.status == 1000) {
                        layer.msg(data.msg, {icon: data.code, time: 1500}, function () {
                            location.href = "/home/goods-list";
                        });
                    } else {
                        layer.msg(data.msg, {icon: data.code});
                    }

                }
            })
            return false;
        });
    });

    layui.use('upload', function(){
        var upload = layui.upload;

        //执行实例
        var uploadInst = upload.render({
            elem: '#image_url',//绑定元素
            url: '/home/goods-img', //上传接口
            done: function(res){
                //上传完毕回调
                alert(res.data);
                $("#file_path").val(res.data);
            },
            error: function(){
                //请求异常回调
            }
        });
    });
</script>
</body>
</html>