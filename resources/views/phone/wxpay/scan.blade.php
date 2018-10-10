<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>微信JS-SDK Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="stylesheet" href="jssdk/style.css?ts=1420774989">
    <link rel="stylesheet" type="text/css" href="/css/loaders.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/loading.css"/>
    <link rel="stylesheet" type="text/css" href="/css/base.css"/>
    <link rel="stylesheet" type="text/css" href="/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/layui/css/layui.css"/>
</head>
<body ontouchstart="">
<div class="lbox_close wxapi_form">
    <h3 id="menu-scan">微信扫一扫</h3>
    <span class="desc">调起微信扫一扫接口</span>
    <button class="btn btn_primary" id="scanQRCode0">scanQRCode(微信处理结果)</button>
    <button class="btn btn_primary" id="scanQRCode1" onclick="scanQRCode()">scanQRCode(直接返回结果)</button>
</div>

<div class="contaniner fixed-contb">
    @foreach($goods_list as $v)
        <section class="shopcar">
            <figure><img src="{{ $v->image_url }}"/></figure>
            <dl>
                <dt>{{ $v->title }}</dt>
                {{--<div class="add">--}}
                    {{--<span onclick="up_num('jian',{{ $v->goods_id }})">-</span>--}}
                    {{--<input type="text" id="num_{{ $v->goods_id }}" value="{{ $v->num }}" />--}}
                    {{--<span onclick="up_num('jia' , {{ $v->goods_id }})">+</span>--}}
                {{--</div>--}}
                <h3>￥<span id="price_{{ $v->goods_id }}">{{ $v->price }}</span></h3>
                {{--<small><img src="/images/shopcar-icon01.png"/></small>--}}
            </dl>
        </section>
@endforeach
<!--去结算-->
<div style="margin-bottom: 16%;"></div>

<div class="shop-go">
    <b>合计：￥<p style="display: inline;" id="price">{{$sum_price}}</p></b>
    @csrf
    <input type="hidden" value="" id="str" />
    <span onclick="buy()"><a href="#">去结算<p style="display:inline;" id="length"></p></a></span>
</div>
</div>


</body>


<script src="//cdn.bootcss.com/layer/3.0.3/layer.min.js"></script>
<script src="https://cdn.bootcss.com/layer/3.1.0/layer.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"> </script>
<script>
    // alert(webhost);
    wx.config({
        url:"{{$signPackage['url']}}",
        debug: false,
        appId: "{{$signPackage['appId']}}",
        timestamp: "{{$signPackage['timestamp']}}",
        nonceStr: "{{$signPackage['nonceStr']}}",
        signature: "{{$signPackage['signature']}}",
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'updateAppMessageShareData',
            'onMenuShareWeibo',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onRecordEnd',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',,
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ]
    });

    // 9 微信原生接口
    // 9.1.1 扫描二维码并返回结果
    document.querySelector('#scanQRCode0').onclick = function () {
        wx.scanQRCode();


        wx.closeWindow();
        // alert();
    };
    // 9.1.2 扫描二维码并返回结果
    function scanQRCode(){
        wx.scanQRCode({
            needResult: 1,
            desc: 'scanQRCode desc',
            success: function (res) {
                // var data = JSON.stringify(res);
                var goods_id = res.resultStr;
                // alert(goods_id);
                $.ajax({
                    type:"post",
                    url:"wx-buy",
                    data:{
                        _token:token,
                        goods_id:goods_id,
                    },
                    cache:false,
                    async:false,
                    success:function (msg){
                        alert(msg.msg);
                        window.location.reload();
                    }
                })
                // alert(res.resultStr);
                // scanQRCode();
            }
        });

    }
    // document.querySelector('#scanQRCode1').onclick = function () {
    //
    // };
</script>
<script src="jssdk/zepto.min.js"></script>
{{--<script src="jssdk/demo.js"></script>--}}
</html>
