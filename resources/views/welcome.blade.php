@extends('main')
@section('title')
    欢迎页
@endsection
@section('link')
@endsection
@section('style')
    <style>
        body{
            padding: 40;
        }
        .title{
            font-size: 20px;
        }
        .btn{
            background: black;
            color: white;
            width: 50px;
            text-align: center;
            cursor: pointer;
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            -webkit-user-select: none;
            user-select: none;
        }
    </style>
@endsection
@section('content')
    <div class="title">
        这里是不欢迎页面
    </div>
    <div class="login-box">
        <div>
            <label for="account">账号：</label>
            <input id="account">
        </div>
        <div>
            <label for="password">密码：</label>
            <input id="password" type="password">
        </div>
        <div class="login-btn btn" style="margin-top: 20px;">登入</div>
    </div>
    <a href="/register">跳到注册页</a>
    <script>
        $('.login-btn').on('click',function(){
            console.log('click');
            var account = $('#account').val();
            var password = $('#password').val();
            $.post('/identify/login',{account: account,password: password},function(data){
                if(data.ec == 200){
                    location.href = '/home'
                }else{
                    alertify.error(data.em);
                }
//                window.location.href = '/';
//                window.location.reload();
//                if(data.ec == 200){
//                if(data == 200){
//                    console.log('成功登入');
//                    //成功登入
//                }else{
//
//                }
            })
        })
    </script>

@endsection