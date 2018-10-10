<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <meta name="format-detection" content="telephone=no" />
    <title>{{ $title }}</title>
    <link rel="stylesheet" type="text/css" href="css/loaders.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/loading.css"/>
    <link rel="stylesheet" type="text/css" href="css/base.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="layui/css/layui.css">
    <script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(window).load(function(){
            $(".loading").addClass("loader-chanage")
            $(".loading").fadeOut(300)


        })
    </script>
</head>
<!--loading页开始-->
<div class="loading">
    <div class="loader">
        <div class="loader-inner pacman">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</div>
<!--loading页结束-->
<body>
<header class="top-header fixed-header">
    <a class="icona" href="javascript:history.go(-1)">
        <img src="images/left.png"/>
    </a>
    <h3>去结算</h3>
    <a class="iconb" href="shopcar.html">
    </a>
</header>

<div class="contaniner fixed-cont">
    <section class="to-buy">
        <header>
            <div class="now">
                <span><img src="images/map-icon.png"/></span>
                <dl>
                    <dt>
                        <b>{{ $info['address_info']->u_name }}</b>
                        <strong>{{ $info['address_info']->phone }}</strong>
                    </dt>
                    <dd>{{ $info['address_info']->province}}{{$info['address_info']->city}}{{$info['address_info']->county}}{{$info['address_info']->address }}</dd>
                    <p>其他地址</p>
                </dl>
            </div>

        </header>
        <div class="buy-list">
            @foreach($info['goods_list'] as $value)
            <ul>
                <a href="detail">
                    <figure>
                        <img src="{{ $value->image_url }}"/>
                    </figure>
                    <li>
                        <h3>{{ $value->title }}</h3>
                        <b>￥{{ $value->price}}</b>
                        <small>×{{ $value->num }}</small>
                    </li>
                </a>
            </ul>
            @endforeach
            <dl>
                <dd>
                    <span>运费</span>
                    <small>包邮</small>
                </dd>
                <dd>
                    <span>商品总额</span>
                    <small>￥{{ $info['price'] }}</small>
                </dd>
                <dt>
                    <textarea rows="4" placeholder="给卖家留言（50字以内）"></textarea>
                </dt>
            </dl>
        </div>

    </section>
    <!--底部不够-->
    <div style="margin-bottom: 9%;"></div>
</div>

<footer class="buy-footer fixed-footer">
    <p>
        @csrf
        <small>总金额</small>
        <b>￥{{ $info['price'] }}</b>
    </p>

    <div>
        <input type="radio" value="1" name="pay_type" onclick="toggle(1)"/>微信

        <input type="radio" value="2" name="pay_type" onclick="toggle(2)"/>支付宝
    </div>

    <div id="jiesuan">
    {{--<a href="alipay/go_pay?price={{ $info['price'] }}&order_id={{ $info['order_id'] }}"><input type="button" value="去付款"/></a>--}}
    <a href="javascript:;"><input type="button" onclick="gopay()" value="去付款"></a>
    </div>

    <div style="position: relative; width: 200px; height: 200px; display: none;" id="qrdiv">
        <img id="qr" src="" width="200" height="200" >
        <div id="message" style="position: absolute; top: 80px; left: 60px; background-color: #6c757d;"></div>
    </div>
</footer>

<script type="text/javascript">
    $(".to-now .tonow label").on('touchstart',function(){
        if($(this).hasClass('ton')){
            $(".to-now .tonow label").removeClass("ton")
        }else{
            $(".to-now .tonow label").addClass("ton")
        }
    })

    function toggle(type){
        if (type == 1){
            {{--$("#jiesuan").html("<a href='http://weixin.pengqq.xyz/payscan/sign.php?pid={{ $info['order_id'] }}_1395184317'><input type='button' value='去付款' /></a>");--}}
            var new_url = "http://weixin.pengqq.xyz/payscan/sign.php?pid={{ $info['order_id'] }}_1395184317";
            document.getElementById('qr').src = new_url;
            $("#jiesuan").html("<a href='javascript:;'><input type='button' value='扫码付款' /></a>");
            $("#qrdiv").css('display' , 'block');
            status('{{ $info['order_id'] }}');
        } else {
            $("#jiesuan").html("<a href='alipay/go_pay?price={{ $info['price'] }}'&order_id={{ $info['order_id'] }}><input type='button' value='去付款' /></a>");
            document.getElementById('qr').src = '';
            $("#qrdiv").css('display' , 'none');
        }
    }

    function status(order_id) {
        // alert(order_id);
        var order_id = order_id;
        token = $("input[name=_token]").val();
        $.ajax({
            url:"order_status",
            type:"post",
            data:{
                _token:token,
                order_id:order_id,
            },
            async: false,
            cache: false,
            success: function (data) {
                if (data.code == 1) {
                    $('#message').html("<img width='20' height='20' src='./img/duihao.png' /><span style='font-size: 20px;color: red;'>扫码成功</span>");
                    setTimeout("status('"+data.msg+"')",2000);
                } else if(data.code == 3){
                    $('#message').html("<img width='20' height='20' src='./img/duihao.png' /><span style='font-size: 20px;color: red;'>支付成功</span>");
                    window.location.href="http://www.pengqq.xyz/order";
                } else {
                    $('#message').html();
                    setTimeout("status('"+data.msg+"')",2000);
                }
            }
        })
        // alert(order_id);
    }

    function gopay(){
        alert("请选择支付方式");
    }
</script>

</body>
</html>