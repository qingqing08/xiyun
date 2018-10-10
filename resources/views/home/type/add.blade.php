@extends('layouts.homeheader')
<script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/layui/layui.js"></script>
<body>
  <div class="x-body">
      <form class="layui-form">
        <div class="layui-form-item">
            @csrf
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>类别名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="name" name="name" required="" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>
            </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">是否显示</label>
          <div class="layui-input-block">
            <input type="checkbox" name="status" lay-skin="switch">
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
              url:"type-add-do",
              type:"post",
              dataType:"json",
              data:{
                  'parent_id':parent_id,
                  'rule_name':rule_name,
                  'rule_url':rule_url,
                  '_token':token,
              },
              cache:false,
              async:false,
              success:function (data){
                  if (data.code == 1) {
                      layer.msg(data.msg, {icon: data.code, time: 1500}, function () {
                          location.href = "/home/type-list";
                      });
                  } else {
                      layer.msg(data.msg, {icon: data.code});
                  }

              }
          })
          return false;
        });
      });
  </script>
</body>
</html>
