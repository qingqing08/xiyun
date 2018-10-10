@extends('layouts.header')
<div>
    <img src="{{ $url }}">
    @csrf
    <input type="hidden" name="code" id="code" value="{{$code}}" />
</div>
<div style="height: 100px;width: 200px;background-color: #00FF00;">
    <span id="message"></span>
</div>

<script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script>
    function run(){
        var code = $("#code").val();
        var token = $("input[name=_token]").val();
        $.ajax({
            url: "is-login",
            type: "post",
            data: {
                'code': code,
                '_token': token,
            },
            async: false,
            cache: false,
            success: function (data) {
                if (data.code == 1) {
                    $('#message').html(data.msg);
                    setTimeout(window.location.href = "http://www.pengqq.xyz/self",2000);
                } else {
                    $('#message').html(data.msg);
                }
            }
        })
        setTimeout('run()',2000);
    }
    run();

</script>