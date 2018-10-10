<html>
<head>
    <meta charset="utf-8">
    <title>微相册</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="stylesheet" href="jssdk/style.css?ts=1420774989">
</head>
<body>
    <div style="width: 100%;height: 100%;padding-left: 10px;padding-top: 10px;">
        <div style="border-radius:2px;width: 40px;height:40px;">
            <a style="cursor: pointer;" onclick="create_album()" ><img src="img/chuangjian.jpg" width="40px" height="40px" alt=""></a>
            @csrf
        </div>

        <br><br>

        @foreach($album_list as $album)
            <div style="text-align:center;border-radius:2px;width: 90px;height:100px;float: left;margin-right: 10px;margin-bottom: 20px;" onblur="up_name_do({{$album['id']}})">
                <a href="photo-list?album_id={{$album['id']}}">
                    <img src="img/wenjianjia.jpg" alt="" width="80px" height="70px">
                </a>
                <input type="type" style="text-align:center;display: none; width:80px;font-size: 20px;" id="in_album_{{$album['id']}}" onblur="up_name_do({{$album['id']}})" value="{{$album['album_name']}}" />
                <span style="text-align:center;font-size: 20px; width:80px;" id="sp_album_{{$album['id']}}" onclick="up_name({{$album['id']}})" >{{$album['album_name']}}</span>
            </div>
        @endforeach
    </div>
    {{--<div class="lbox_close wxapi_form">--}}

        {{--<span class="desc">判断当前客户端是否支持指定JS</span>--}}
        {{--<button class="btn btn_primary" id="checkJsApi">checkJsApi</button>--}}

        {{--<span class="desc">拍照或从手机相册中选图</span>--}}
        {{--<button class="btn btn_primary" id="chooseImage">chooseImage</button>--}}
        {{--<span class="desc">预览图片</span>--}}
        {{--<button class="btn btn_primary" id="previewImage">previewImage</button>--}}
        {{--<span class="desc">上传图片</span>--}}
        {{--<button class="btn btn_primary" id="uploadImage">uploadImage</button>--}}
        {{--<span class="desc">下载图片</span>--}}
        {{--<button class="btn btn_primary" id="downloadImage">downloadImage</button>--}}
    {{--</div>--}}
</body>
</html>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"> </script>
<script src="js/jquery-3.3.1.min.js"> </script>
<script>

    function up_name(album_id) {
        $("#in_album_"+album_id).css("display" , "block");
        $("#sp_album_"+album_id).css("display" , "none");
        $("#in_album_"+album_id).focus();
    }

    function up_name_do(album_id){
        var token = $("input[name=_token]").val();
        var album_name = $("#in_album_"+album_id).val();
        $.ajax({
            type:"post",
            url:"up-name",
            data:{
                _token:token,
                album_id:album_id,
                album_name:album_name,
            },
            async:false,
            cache:false,
            success:function (data){
                if (data.code == 1){
                    $("#in_album_"+album_id).css("display" , "none");
                    $("#sp_album_"+album_id).html(data.album_name);
                    $("#sp_album_"+album_id).css("display" , "block");
                }
                // alert(data.msg);
            }
        })
    }

    function create_album(){
        var token = $("input[name=_token]").val();
        $.ajax({
            type:"post",
            url:"create-album",
            data:{
                _token:token,
                openid:"{{$openid}}",
            },
            async:false,
            cache:false,
            success:function (data){
                // alert(data.msg);
                window.location.reload();
            }
        })
    }
</script>
<script>
    wx.config({
        url:"{{$signPackage['url']}}",
        debug: true,
        appId: "{{$signPackage['appId']}}",
        timestamp: "{{$signPackage['timestamp']}}",
        nonceStr: "{{$signPackage['nonceStr']}}",
        signature: "{{$signPackage['signature']}}",
        jsApiList: [
            'checkJsApi',
            // 'onMenuShareTimeline',
            // 'onMenuShareAppMessage',
            // 'onMenuShareQQ',
            // 'updateAppMessageShareData',
            // 'onMenuShareWeibo',
            // 'hideMenuItems',
            // 'showMenuItems',
            // 'hideAllNonBaseMenuItem',
            // 'showAllNonBaseMenuItem',
            // 'translateVoice',
            // 'startRecord',
            // 'stopRecord',
            // 'onRecordEnd',
            // 'playVoice',
            // 'pauseVoice',
            // 'stopVoice',
            // 'uploadVoice',,
            // 'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            // 'getNetworkType',
            // 'openLocation',
            // 'getLocation',
            // 'hideOptionMenu',
            // 'showOptionMenu',
            // 'closeWindow',
            // 'scanQRCode',
            // 'chooseWXPay',
            // 'openProductSpecificView',
            // 'addCard',
            // 'chooseCard',
            // 'openCard'
        ]
    });
</script>
<script src="jssdk/zepto.min.js"></script>
{{--<script src="jssdk/album.js"></script>--}}