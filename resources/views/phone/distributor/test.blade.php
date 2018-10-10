<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>微信JS-SDK Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="stylesheet" href="jssdk/style.css?ts=1420774989">
</head>
<body ontouchstart="">
<div class="wxapi_container">
    <div class="wxapi_index_container">
        <ul class="label_box lbox_close wxapi_index_list">
            <li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-basic">基础接口</a></li>
            <li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-share">分享接口</a></li>
            {{--<li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-image">图像接口</a></li>--}}
            {{--<li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-voice">音频接口</a></li>--}}
            {{--<li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-smart">智能接口</a></li>--}}
            {{--<li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-device">设备信息接口</a></li>--}}
            {{--<li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-location">地理位置接口</a></li>--}}
            {{--<li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-webview">界面操作接口</a></li>--}}
            {{--<li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-scan">微信扫一扫接口</a></li>--}}
            {{--<li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-shopping">微信小店接口</a></li>--}}
            {{--<li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-card">微信卡券接口</a></li>--}}
            {{--<li class="label_item wxapi_index_item"><a class="label_inner" href="#menu-pay">微信支付接口</a></li>--}}
        </ul>
    </div>
    <div class="lbox_close wxapi_form">
        <h3 id="menu-basic">基础接口</h3>
        <span class="desc">判断当前客户端是否支持指定JS接口</span>
        <button class="btn btn_primary" id="checkJsApi">checkJsApi</button>

        <h3 id="menu-share">分享接口</h3>
        <span class="desc">获取“分享到朋友圈”按钮点击状态及自定义分享内容接口</span>
        <button class="btn btn_primary" id="onMenuShareTimeline">onMenuShareTimeline</button>
        <span class="desc">获取“分享给朋友”按钮点击状态及自定义分享内容接口</span>
        <button class="btn btn_primary" id="onMenuShareAppMessage">onMenuShareAppMessage</button>
        <span class="desc">获取“分享到QQ”按钮点击状态及自定义分享内容接口</span>
        <button class="btn btn_primary" id="onMenuShareQQ">onMenuShareQQ</button>
        <span class="desc">获取“分享到腾讯微博”按钮点击状态及自定义分享内容接口</span>
        <button class="btn btn_primary" id="onMenuShareWeibo">onMenuShareWeibo</button>
        <span class="desc">获取“分享QQ空间”</span>
        <button class="btn btn_primary" id="onMenuShareQZone">onMenuShareQZone</button>

        <h3 id="menu-image">图像接口</h3>
        <span class="desc">拍照或从手机相册中选图接口</span>
        <button class="btn btn_primary" id="chooseImage" onclick="chooseImage()">chooseImage</button>
        <span class="desc">预览图片接口</span>
        <button class="btn btn_primary" id="previewImage">previewImage</button>
        <span class="desc">上传图片接口</span>
        <button class="btn btn_primary" id="uploadImage">uploadImage</button>
        <span class="desc">下载图片接口</span>
        <button class="btn btn_primary" id="downloadImage">downloadImage</button>

        <h3 id="menu-voice">音频接口</h3>
        <span class="desc">开始录音接口</span>
        <button class="btn btn_primary" id="startRecord">startRecord</button>
        <span class="desc">停止录音接口</span>
        <button class="btn btn_primary" id="stopRecord">stopRecord</button>
        <span class="desc">播放语音接口</span>
        <button class="btn btn_primary" id="playVoice">playVoice</button>
        <span class="desc">暂停播放接口</span>
        <button class="btn btn_primary" id="pauseVoice">pauseVoice</button>
        <span class="desc">停止播放接口</span>
        <button class="btn btn_primary" id="stopVoice">stopVoice</button>
        <span class="desc">上传语音接口</span>
        <button class="btn btn_primary" id="uploadVoice">uploadVoice</button>
        <span class="desc">下载语音接口</span>
        <button class="btn btn_primary" id="downloadVoice">downloadVoice</button>

        <h3 id="menu-smart">智能接口</h3>
        <span class="desc">识别音频并返回识别结果接口</span>
        <button class="btn btn_primary" id="translateVoice">translateVoice</button>

        <h3 id="menu-device">设备信息接口</h3>
        <span class="desc">获取网络状态接口</span>
        <button class="btn btn_primary" id="getNetworkType">getNetworkType</button>

        <h3 id="menu-location">地理位置接口</h3>
        <span class="desc">使用微信内置地图查看位置接口</span>
        <button class="btn btn_primary" id="openLocation">openLocation</button>
        <span class="desc">获取地理位置接口</span>
        <button class="btn btn_primary" id="getLocation">getLocation</button>

        <h3 id="menu-webview">界面操作接口</h3>
        <span class="desc">隐藏右上角菜单接口</span>
        <button class="btn btn_primary" id="hideOptionMenu">hideOptionMenu</button>
        <span class="desc">显示右上角菜单接口</span>
        <button class="btn btn_primary" id="showOptionMenu">showOptionMenu</button>
        <span class="desc">关闭当前网页窗口接口</span>
        <button class="btn btn_primary" id="closeWindow">closeWindow</button>
        <span class="desc">批量隐藏功能按钮接口</span>
        <button class="btn btn_primary" id="hideMenuItems">hideMenuItems</button>
        <span class="desc">批量显示功能按钮接口</span>
        <button class="btn btn_primary" id="showMenuItems">showMenuItems</button>
        <span class="desc">隐藏所有非基础按钮接口</span>
        <button class="btn btn_primary" id="hideAllNonBaseMenuItem">hideAllNonBaseMenuItem</button>
        <span class="desc">显示所有功能按钮接口</span>
        <button class="btn btn_primary" id="showAllNonBaseMenuItem">showAllNonBaseMenuItem</button>

        <h3 id="menu-scan">微信扫一扫</h3>
        <span class="desc">调起微信扫一扫接口</span>
        <button class="btn btn_primary" id="scanQRCode0">scanQRCode(微信处理结果)</button>
        <button class="btn btn_primary" id="scanQRCode1">scanQRCode(直接返回结果)</button>

        <h3 id="menu-shopping">微信小店接口</h3>
        <span class="desc">跳转微信商品页接口</span>
        <button class="btn btn_primary" id="openProductSpecificView">openProductSpecificView</button>

        <h3 id="menu-card">微信卡券接口</h3>
        <span class="desc">批量添加卡券接口</span>
        <button class="btn btn_primary" id="addCard">addCard</button>
        <span class="desc">调起适用于门店的卡券列表并获取用户选择列表</span>
        <button class="btn btn_primary" id="chooseCard">chooseCard</button>
        <span class="desc">查看微信卡包中的卡券接口</span>
        <button class="btn btn_primary" id="openCard">openCard</button>

        <h3 id="menu-pay">微信支付接口</h3>
        <span class="desc">发起一个微信支付请求</span>
        <button class="btn btn_primary" id="chooseWXPay">chooseWXPay</button>
    </div>
</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"> </script>
<script>
    // alert(webhost);
    wx.config({
        url:"{{$signPackage['url']}}",
        debug: true,
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

    {{--wx.ready(function () {--}}
        {{--// 1 判断当前版本是否支持指定 JS 接口，支持批量判断--}}
        {{--document.querySelector('#checkJsApi').onclick = function () {--}}
            {{--wx.checkJsApi({--}}
                {{--jsApiList: [--}}
                    {{--'getNetworkType',--}}
                    {{--'previewImage'--}}
                {{--],--}}
                {{--success: function (res) {--}}
                    {{--alert(JSON.stringify(res));--}}
                {{--}--}}
            {{--});--}}
        {{--};--}}

        {{--// 2. 分享接口--}}
        {{--// 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口--}}
        {{--wx.updateAppMessageShareData({--}}
            {{--title: '希芸--您身边的美丽顾问',--}}
            {{--desc: '在长大的过程中，我才慢慢发现，我身边的所有事，别人跟我说的所有事，那些所谓本来如此，注定如此的事，它们其实没有非得如此，事情是可以改变的。更重要的是，有些事既然错了，那就该做出改变。',--}}
            {{--link: "http://www.pengqq.xyz/self?userid="+"{{$user_info['id']}}",--}}
            {{--imgUrl: 'http://www.pengqq.xyz/jssdk/7303a3dce50cca45b1f62018d2198dd8.jpg',--}}
            {{--trigger: function (res) {--}}
                {{--// 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回--}}
                {{--alert('用户点击发送给朋友');--}}
            {{--},--}}
            {{--// success: function (res) {--}}
            {{--//     alert('已分享');--}}
            {{--// },--}}
            {{--cancel: function (res) {--}}
                {{--alert('已取消');--}}
            {{--},--}}
            {{--fail: function (res) {--}}
                {{--alert(JSON.stringify(res));--}}
            {{--}--}}
        {{--});--}}
        {{--// alert('已注册获取“发送给朋友”状态事件');--}}

        {{--wx.onMenuShareTimeline({--}}
            {{--title: '互联网之子',--}}
            {{--link: 'http://www.pengqq.xyz/self?userid="+"{{$user_info['id']}}',--}}
            {{--imgUrl: 'http://www.pengqq.xyz/jssdk/7303a3dce50cca45b1f62018d2198dd8.jpg',--}}
            {{--trigger: function (res) {--}}
                {{--// 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回--}}
                {{--alert('用户点击分享到朋友圈');--}}
            {{--},--}}
            {{--success: function (res) {--}}
                {{--alert('已分享');--}}
            {{--},--}}
            {{--cancel: function (res) {--}}
                {{--alert('已取消');--}}
            {{--},--}}
            {{--fail: function (res) {--}}
                {{--alert(JSON.stringify(res));--}}
            {{--}--}}
        {{--});--}}
        {{--// alert('已注册获取“分享到朋友圈”状态事件');--}}

        {{--var shareData = {--}}
            {{--title: '微信JS-SDK Demo',--}}
            {{--desc: '微信JS-SDK,帮助第三方为用户提供更优质的移动web服务',--}}
            {{--link: 'http://www.pengqq.xyz/self',--}}
            {{--imgUrl: ''--}}
        {{--};--}}
        {{--wx.updateAppMessageShareData(shareData);--}}
        {{--wx.onMenuShareTimeline(shareData);--}}
    {{--});--}}


    function chooseImage(){
        wx.chooseImage({
            count: 5, // 默认9
            sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                var img = document.createElement('img');
                img.src=localIds[0];
                img.style.width="400px";
                document.body.appendChild(img);
                // alert(localIds);
                // previewImage(localIds[0],localIds);
                // uploadImage(localIds[0]);
            }
        });
    }

    function previewImage(currentId , localIds){
        wx.previewImage({

            current:currentId,
            urls:localIds,
        });
    }

    function uploadImage(localId){
        wx.uploadImage({
            localId: localId, // 需要上传的图片的本地ID，由chooseImage接口获得
            isShowProgressTips: 1, // 默认为1，显示进度提示
            success: function (res) {
                var serverId = res.serverId; // 返回图片的服务器端ID
            }
        });
    }

    wx.error(function (res) {
        // alert(1);
        alert(res.errMsg);
    });
</script>
<script src="jssdk/zepto.min.js"></script>
<script src="jssdk/demo.js"></script>
</html>