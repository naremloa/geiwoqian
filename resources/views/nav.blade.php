<style>
    .header-nav{
        width: 100%;
        height: 50px;
        background: black;
        position: fixed;
        top: 0;
        left: 0;
        /*-webkit-font-smoothing: antialiased;*/
        /*-moz-osx-font-smoothing: grayscale;*/
    }
    .header-nav .web-only{
        display: block;
    }
    .header-nav .mobile-only{
        display: none;
    }
    .header-nav .logo{
        line-height: 50px;
    }
    @media screen and (max-width: 900px) {
        .header-nav .web-only{
            display: none;
        }
        .header-nav .mobile-only{
            display: block;
        }
    }
    .header-nav .nav-wrapper{
        width: 100%;
        height: 50px;
        margin: 0 auto;
        display: block;
    }
    .header-nav .nav-wrapper .user-block{
        position: absolute;
        right: 0;
        top: 0;
        margin-right: 40px;
        height: 50px;
        white-space: nowrap;
    }
    .header-nav .nav-wrapper .user-block a{
        line-height: 50px;
    }
    .header-nav .nav-wrapper .user-block .user-site{
        width: 50px;
        height: 50px;
    }
    .header-nav .nav-wrapper .user-block .user-avatar-box{
        width: 36px;
        height: 36px;
        display: inline-block;
        border-radius: 1000px;
        margin: 7px;
    }
    .header-nav .nav-mobile{
        text-align: center;
        height: 50px;
        width: 100%;
    }
</style>
<nav class="header-nav">
    <div class="mobile-only">
        <div class="nav-mobile">
            <a class="logo" href="/home"><span class="ft-color-white fs32">GeiWoQian</span></a>
        </div>
    </div>
    <div class="web-only">
        <div class="nav-wrapper relative">
            <a class="logo ml-40" href="/home"><span class="ft-color-white fs32">GeiWoQian</span></a>
            <a class="logout ml-40 ft-color-white pointer" href="/identify/logout">注销</a>
            <div class="user-block clearfix">
                @if($user['role'] != 3)
                    <div class="fl mr-10">
                        <a class="ft-color-white il-block become-producer pointer">成为发起者</a>
                    </div>
                @else
                    <div class="fl mr-10">
                        <a class="ft-color-white il-block" href="/post">发布内容</a>
                    </div>
                    <div class="fl mr-10">
                        <a class="ft-color-white il-block" href="/producer">发起者页面</a>
                    </div>
                @endif
                <div class="user-site fl">
                    <span class="user-avatar-box">
                        @if($user['avatar'] == 'default')
                            <img class="wh100" src="/img/default_avatar.png">
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    $(function(){
        $('.header-nav .become-producer').on('click',function(){
            $.post('/apply/producer',function(data){

            })
        })
        $('.header-nav .logout').on('click',function(){
//            $.post('/post/logout',function(data){
//                if(data.ec == 200){
//                    location.href='/';
//                    window.location.reload();
//                }
//            })
        })
    })
</script>