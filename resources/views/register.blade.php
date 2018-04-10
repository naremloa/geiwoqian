@extends('main')
@section('title')
    註冊頁
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
        這裡是註冊頁
    </div>
    <div class="register-box">
        <div>
            <label for="account">郵箱：</label>
            <input id="account">
        </div>
        <div>
            <label for="name">暱稱：</label>
            <input id="name">
        </div>
        <div>
            <label for="password">密碼：</label>
            <input id="password" type="password">
        </div>
        <div>
            <label for="identify">驗證口令</label>
            <input id="identify" type="identify">
        </div>
        <div class="register-btn btn" style="margin-top: 20px;">ע��</div>
    </div>
    <a href="/">跳到登入頁</a>
    <script>
        $('.register-btn').on('click',function(){
               var account = $('#account').val();
            var password = $('#password').val();
            var name = $('#name').val();
            var identify = $('#identify').val();
            $.post('/register/post',{account: account,name: name,password: password,identify: identify},function(data){
                if(data.ec == 200){
                    location.href = "/";
                }
            });
        })
    </script>
@endsection