@extends('layouts.header')
	<header class="top-header">
		<a class="text texta" href="index">取消</a>
		<h3>登录</h3>
		<a class="text" href="register">注册</a>
	</header>

	<div class="login">
		<form action="#">
			<ul>
				<li>
					@csrf
					<img src="/images/login.png"/>
					<label>账号</label>
					<input type="text" name="username" id="username" placeholder="请输入账号"/>
				</li>
				<li>
					<img src="/images/password.png"/>
					<label>密码</label>
					<input type="password" name="password" id="password" placeholder="请输入密码"/>
				</li>
			</ul>
			<input type="submit" lay-submit lay-filter="formDemo" value="登录" />
            <a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx996fa85abda5e676&redirect_uri=http://www.pengqq.xyz/wx_login?response_type=code&scope=snsapi_userinfo&state=STATEA#wechat_redirect">微信登录</a>
		    <a href="sm-login">微信扫码登录</a>
            <a target='_blank' href='https://api.weibo.com/oauth2/authorize?client_id=3817437085&redirect_uri=http://api.jinxiaofei.xyz/auth&response_type=code'>微博登陆</a>
        </form>
	</div>

</body>
</html>
<script src="/layui/layui.js"></script>
<script type="text/javascript">
    layui.use('form', function(){
        var form = layui.form;
		//  监听提交
        form.on('submit(formDemo)', function(data){
            var username = $("#username").val();
            var password = $("#password").val();
            var token = $("input[name=_token]").val();
            $.ajax({
                url:"login_do",
                type:"post",
                dataType:"json",
                data:{
                    'username':username,
					'password':password,
					'_token':token,
                },
                cache:false,
                async:false,
                success:function (data){
                    // alert(data);
                    if (data.code == 1) {
                        layer.msg(data.msg, {icon: data.code, time: 1500}, function () {
                            location.href = "/";
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
