<html>
<head>
    <meta charset="utf-8">
    <title>微相册</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="stylesheet" href="jssdk/style.css?ts=1420774989">
</head>
<body>
<div style="width: 100%;height: 100%;padding-left: 10px;padding-top: 10px;">
    <div style="border-radius:2px;width: 150px;height:40px;">
        <a style="cursor: pointer;" id="chooseImage" ><img src="img/jiahao.jpg" width="40px" height="40px" alt=""></a>
        @csrf
        <a href="album-list">返回相册</a>
    </div>

    <br><br>

    @foreach($photo_list as $photo)
        <div style="align:center;border:2px solid skyblue;border-radius:2px;width: 150px;float: left;margin-right: 10px;margin-bottom: 20px;">
            <img src="{{$photo['url']}}" alt="" width="150px">
            {{--<input type="type" style="text-align:center;display: none; width:80px;font-size: 20px;" id="in_album_{{$album['id']}}" onblur="up_name_do({{$album['id']}})" value="{{$album['album_name']}}" />--}}
            {{--<span style="text-align:center;font-size: 20px; width:80px;" id="sp_album_{{$album['id']}}" onclick="up_name({{$album['id']}})" >{{$album['album_name']}}</span>--}}
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

    var images = {
        localId: [],
        serverId: []
    };
    document.querySelector('#chooseImage').onclick = function () {
        wx.chooseImage({
            success: function (res) {
                images.localId = res.localIds;
                localId = res.localIds;
                alert('已选择 ' + res.localIds.length + ' 张图片');
                uploadImage();
            }
        });
    };

    function uploadImage(){
        var token = $("input[name=_token]").val();
        if (images.localId.length == 0) {
            alert('请先使用 chooseImage 接口选择图片');
            return;
        }
        var i = 0, length = images.localId.length;
        images.serverId = [];
        function upload() {
            wx.uploadImage({
                localId: images.localId[i],
                success: function (res) {
                    i++;
                    //alert('已上传：' + i + '/' + length);
                    images.serverId.push(res.serverId);
                    $.ajax({
                        type:"post",
                        url:"get-photo",
                        data:{
                            _token:token,
                            serverId:res.serverId,
                            album_id:"{{$album_id}}",
                        },
                        async:false,
                        cache:false,
                        success:function (data){
                            alert(data);
                        }
                    })
                    if (i < length) {
                        // alert($("input[name=_token]").val());
                        upload();
                    }
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        }
        upload();
    };
</script>
<script src="jssdk/zepto.min.js"></script>
{{--<script src="jssdk/album.js"></script>--}}