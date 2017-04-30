<style>
    .header-nav{
        z-index: 1000;
        width: 100%;
        height: 50px;
        background: rgba(44, 44, 65, 1);
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .2);
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
    .header-nav .nav-wrapper .user-block .user-site .user-menu{
        position: absolute;
        width: 120px;
        left: -35px;
        background: rgba(44, 44, 65, 1);
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .2);
        padding: 5px 20px;
        box-sizing: border-box;
    }
    .header-nav .nav-wrapper .user-block .user-site .user-menu li{
        cursor: pointer;
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
            <a class="logo" href="/home"><span class="ft-color-white fs32">GeiWoQian</span><span class="ft-color-white ml-10">later</span></a>
        </div>
    </div>
    <div class="web-only">
        <div class="nav-wrapper relative">
            <a class="logo ml-40" href="/home"><span class="ft-color-white fs32">GeiWoQian</span><span class="ft-color-white ml-10">later</span></a>
            <a class="logout ml-40 ft-color-white pointer" href="/identify/logout">注销</a>
            <div class="user-block clearfix">
                @if($user['role'] != 3)
                    <div class="fl mr-10">
                        <a class="ft-color-white il-block become-producer pointer">成为发起者</a>
                    </div>
                @else
                    <div class="fl mr-10">
                        <a class="ft-color-white il-block" href="/postpage">发布内容</a>
                    </div>
                    <div class="fl mr-10">
                        <a class="ft-color-white il-block" href="/producer/{{$user['producer_info']['url_slug']}}">发起者页面</a>
                    </div>
                @endif
                <div class="user-site fl relative">
                    <span class="user-avatar-box">
                          <img class="wh100 circle" src="/img/default_avatar.png">
                    </span>
                    <div class="user-menu">
                        <ul>
                            <li><a>home页</a></li>
                            <li><a>设置</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    let isWindowfocus = true;
    //短轮询，间隔时间5秒
    let checkoutTime = 5000;
    //判断页面当时是否被聚焦，若无则没必要再向接口请求数据，继续轮询
    $(window).on('blur',function(){
        isWindowfocus = false;
    });
    $(window).on('focus',function(){
        isWindowfocus = true;
    });
//    短轮询循环方法，在页面聚焦时每过一个checkoutTime，就向服务器的通知接口请求数据
    function getNotify(){
        console.log(isWindowfocus);
        if(isWindowfocus){
            $.get('/user/notify/get-check', function(data){
                if(data.ec == 200){
                    console.log(data);
                }
                setTimeout(function(data){
                    getNotify();
                }, checkoutTime);
            })
        }else{
            setTimeout(function(data){
                getNotify();
            }, checkoutTime);
        }
    }
//    页面载入后间隔1秒，短轮询开始
    setTimeout(function(data){
        getNotify();
    },1000);


    $(function(){
        $('.header-nav .become-producer').on('click',function(){
            $.post('/apply/producer',function(data){
                if(data.ec == 200){
                    location.href = "/";
                }
            })
        });
        var user_menu_go = null;
        $('.header-nav .user-block .user-site').on('mouseenter',function(){
            user_menu_go = null;
            $(this).find('.user-menu').removeClass('hide');
        }).on('mouseleave',function(){
            var _this = this;
            user_menu_go = setTimeout(function(){
                $(_this).find('.user-menu').addClass('hide');
            },300);
        })
    })
</script>