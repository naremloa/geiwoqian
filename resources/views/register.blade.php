@extends('main')
@section('title')
    注册页
@endsection
@section('link')
@endsection
@section('style')
    <style>
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
        这里是注册页
    </div>
    <div class="register-box">
        <div>
            <label for="email">邮箱：</label>
            <input id="email">
        </div>
        <div>
            <label for="account">账号：</label>
            <input id="account">
        </div>
        <div>
            <label for="password">密码：</label>
            <input id="password" type="password">
        </div>
        <div class="register-btn btn" style="margin-top: 20px;">注册</div>
    </div>
    <a href="/">跳到登入页</a>
    <script>
        $('.register-btn').on('click',function(){
            var account = $('#account').val();
            var password = $('#password').val();
            var email = $('#email').val();
            $.post('/register/post',{account: account,password: password,email: email},function(data){

            });
        })
    </script>
@endsection