<style>
    .vm-nav{
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
    .vm-nav .web-only{
        display: block;
    }
    .vm-nav .mobile-only{
        display: none;
    }
    .vm-nav .logo{
        line-height: 50px;
    }
    @media screen and (max-width: 900px) {
        .vm-nav .web-only{
            display: none;
        }
        .vm-nav .mobile-only{
            display: block;
        }
    }
    .vm-nav .nav-wrapper{
        width: 100%;
        height: 50px;
        margin: 0 auto;
        display: block;
    }
    .vm-nav .nav-wrapper .user-block{
        position: absolute;
        right: 0;
        top: 0;
        margin-right: 40px;
        height: 50px;
        white-space: nowrap;
    }
    .vm-nav .nav-wrapper .user-block a{
        line-height: 50px;
    }
    .vm-nav .nav-wrapper .user-block .user-site{
        width: 50px;
        height: 50px;
    }
    .vm-nav .nav-wrapper .user-block .user-avatar-box{
        width: 36px;
        height: 36px;
        display: inline-block;
        border-radius: 1000px;
        margin: 7px;
    }
    .vm-nav .nav-wrapper .user-block .user-site .user-menu{
        position: absolute;
        width: 120px;
        left: -35px;
        background: rgba(44, 44, 65, 1);
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .2);
        padding: 5px 20px;
        box-sizing: border-box;
    }
    .vm-nav .nav-wrapper .user-block .user-site .user-menu li{
        cursor: pointer;
    }
    .vm-nav .nav-mobile{
        text-align: center;
        height: 50px;
        width: 100%;
    }
</style>
<template>
    <nav class="vm-nav">
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
                    <template v-if="user.role == 3">
                        <div class="fl mr-10">
                            <a class="ft-color-white il-block" href="/postpage">发布内容</a>
                        </div>
                        <div class="fl mr-10">
                            <a class="ft-color-white il-block" :href="'/producer/' + user.producer_info.url_slug">发起者页面</a>
                        </div>
                    </template>
                    <template v-else>
                        <div class="fl mr-10">
                            <a class="ft-color-white il-block become-producer pointer" @click="method_becom_producer">成为发起者</a>
                        </div>
                    </template>
                    <div class="user-site fl relative" @mouseenter="method_user_menu_mouseenter($event)" @mouseleave="method_user_menu_mouseleave($event)">
                            <span class="user-avatar-box">
                                <img class="wh100 circle" src="/img/default_avatar.png">
                            </span>
                        <div class="user-menu hide">
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
</template>
<script>
    export default{
        props:['user'],
        data(){
            return{
                isWindowfocus: true,
                //短轮询，间隔时间5秒
                checkoutTime: 5000,
                user_menu_go: null,
                notify_list: [],
                notify_unread: 0,
            }
        },
        created(){
            let _this = this;
            //判断页面当时是否被聚焦，若无则没必要再向接口请求数据，继续轮询
            $(window).on('blur',function(){
                _this.isWindowfocus = false;
            });
            $(window).on('focus',function(){
                _this.isWindowfocus = true;
            });

            //    页面载入后间隔1秒，短轮询开始
            setTimeout(function(data){
                _this.getNotify(true);
            },1000);
        },
        methods:{
            getNotify(init){
                let _this = this;
                if(_this.isWindowfocus){
                    $.get('/user/notify/get-check', function(data){
                        if(data.ec == 200){
                            if(init){
                                _this.notify_list = data.data.notify;
                                _this.notify_unread = data.data.notify_unread_num;
                            }
                            else if(data.data.notify_unread_num > 0 && _this.notify_unread != data.data.notify_unread_num){
                                for(let i = 0; i < data.data.notify_unread_num; i++){
                                    if(data)
                                    _this.notify_list.push(data.data.notify[i]);
                                }
                                _this.notify_unread = data.data.notify_unread_num;
                            }
                        }
                        setTimeout(function(data){
                            _this.getNotify(false);
                        }, _this.checkoutTime);
                    })
                }else{
                    setTimeout(function(data){
                        _this.getNotify(false);
                    }, _this.checkoutTime);
                }
            },
            method_become_producer(){
                $.post('/apply/producer',function(data){
                    if(data.ec == 200){
                        location.href = "/";
                    }
                })
            },
            method_user_menu_mouseenter(event){
                this.user_menu_go = null;
                $(event.target).find('.user-menu').removeClass('hide');
            },
            method_user_menu_mouseleave(event){
                this.user_menu_go = setTimeout(function(){
                    $(event.target).find('.user-menu').addClass('hide');
                },300);
            },
        }

    }
</script>