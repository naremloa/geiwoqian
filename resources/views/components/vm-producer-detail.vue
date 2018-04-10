<style>
    .vm-producer-detail .producer-head{
        height: 90px;
        background: rgba(248, 248, 248, 1);
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .2);
        color: #5a5a68;
    }
    .vm-producer-detail .producer-head .producer-avatar{
        width: 128px;
        height: 128px;
        margin-top: -64px;
    }
    .vm-producer-detail .producer-head .producer-avatar img{
        border: 3px solid rgb(248,248,248);
    }
    .vm-producer-detail .producer-head .producer-name-box{
        height: 100%;
    }
    .vm-producer-detail .producer-head .follow-btn{
        height: 30px;
        margin-top: 10px;
        line-height: 20px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .5);
    }
    .vm-producer-detail .producer-head .contribute-btn{
        height: 30px;
        margin-top: 10px;
        line-height: 20px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .5);
    }
    .vm-producer-detail .producer-body .head p{
        padding: 10px;
        box-sizing: border-box;
        cursor: pointer;
    }
    .vm-producer-detail .producer-body .head p.on{
        background: rgb(236, 116, 116);
        color: white;
    }
    .vm-producer-detail .producer-body .producer-intro{
        line-height: 1.7;
    }
    .vm-producer-detail .producer-body .cl-card .is_producer_setting{
        display: none;
        cursor: pointer;
    }
    .vm-producer-detail .producer-body .cl-card:hover .is_producer_setting{
        display: block;
    }
    .vm-producer-detail .line-2{
        border-top: 1px solid rgba(255, 255, 255, .2);
        width: 100%;
        margin: 20px 0;
    }
    .tag-box p{
        background: rgba(248, 248, 248, 1);
        margin-right: 10px;
    }
</style>
<template>
    <section class="vm-producer-detail">
        <div class="cl-bg" style="height: 20vw; background-size: cover; background-position: center;" :style="{backgroundImage:'url(' + producer_info['cover'] +')'}">
        </div>
        <!--<div class="cl-bg" style="height: 300px; background-size: cover" :style="{backgroundImage:'url(' + 'http://onmpkkp5b.bkt.clouddn.com/e6602cf40ef383346d6a2434ba411eae.jpeg' +')'}">-->
        <!--</div>-->
        <div class="producer-head">
            <div class="cl-wrap flex-box">
                <div class="producer-avatar">
                    <img class="circle wh100" :src="producer_info['avatar']">
                </div>
                <div class="producer-name-box ml-20">
                    <div class="producer-name fs20 mt-10">{{producer_info['name']}}</div>
                    <div class="flex-box">
                        <template v-if="is_follow == 1">
                            <div class="cl-btn follow-btn" onselectstart="return false" @click="method_follow_remove">取关</div>
                        </template>
                        <template v-else-if="is_follow == 0">
                            <div class="cl-btn follow-btn" onselectstart="return false" @click="method_follow">关注</div>
                        </template>
                        <a class="cl-btn contribute-btn ml-20" onselectstart="return false" :href="'/producer/'+producer_info.url_slug+'/edit-reward'">成为支持者</a>
                    </div>
                </div>
                <div class="flex-item-1"></div>
            </div>
        </div>
        <div class="producer-body cl-wrap flex-box pt-40">
            <div class="cl-wrap-left">
                <!--<template v-if="producer_info['intro'] === ''">-->
                    <!--<div class="producer-intro cl-card" style="box-shadow: 0 1px 0 0 rgba(0, 0, 0, .2);">-->
                        <!--<p>这是一块介绍面板</p>-->
                        <!--<p>这是一块介绍面板</p>-->
                        <!--<p>这是一块介绍面板</p>-->
                        <!--<p>这是一块介绍面板</p>-->
                        <!--<p>这是一块介绍面板</p>-->
                    <!--</div>-->
                <!--</template>-->
                <!--<template v-else>-->
                    <!--<div class="producer-intro cl-card">{{producer_info['intro']}}</div>-->
                <!--</template>-->

                <div class="head" style="color: #5a5a68;">
                    <div class="flex-box">
                        <p class="on">全部内容</p>
                        <div style="width: 140px; line-height: 34px; margin-left: 10px; border: 1px solid rgba(220, 220, 220, 1); position: relative; background: rgba(248, 248, 248, 1); color: #5a5a68; padding: 0 10px;">
                            skangksandg
                            <i class="fa fa-chevron-down" style="position: absolute; right: 10px; top: 10px;"></i>
                        </div>
                    </div>
                    <div class="tag-box flex-box" style="margin: 10px 0 30px 0 ;">
                        <p v-for="tag in tag_post">{{ tag.name }}</p>
                    </div>
                </div>
                <div class="content">
                    <vm-feed-card v-for="data in feeds" :data="data" v-bind="data.id"></vm-feed-card>
                </div>
            </div>
            <div class="cl-wrap-right">
                <div class="cl-card">
                    <div class="flex-box">
                        <div class="flex-item-1" style="text-align: center;">
                            <p>支持者</p>
                            <p class="fs20 fwb mt-10">{{producer_info['backer_count']}}</p>
                        </div>
                        <div style="border-left: 2px solid rgb(232, 232, 232);">
                        </div>
                        <div class="flex-item-1" style="text-align: center;">
                            <p>被给</p>
                            <p class="mt-10"><span class="fwb fs20">￥{{producer_info['get_fund_per_month']}}</span> / 月</p>
                        </div>
                    </div>
                </div>
                <div class="cl-card">
                    <div class="flex-box mb-20" style="line-height: 20px;">
                        <p class="fs20 flex-item-1">奖励</p>
                        <p class="is_producer_setting" v-if="is_producer == 1"><a href="/producer/setting/edit" target="_blank">设置</a></p>
                    </div>
                    <template v-for="(data, index) in reward">
                        <p class="fs20">{{data.reward_title}}</p>
                        <p class="mt-10 white-2">￥{{data.reward_fund}} 或更多</p>
                        <div class="mt-10">{{data.reward_description}}</div>
                        <div class="flex-box mt-10">
                            <a class="cl-btn" :href="'/producer/' + producer_info.url_slug + '/edit-reward?reward_card=' + index">支持 | ￥{{data.reward_fund}}</a>
                        </div>
                        <div v-if="index < reward.length - 1" class="line-2"></div>
                    </template>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
    let vmFeedCard = require('../components/vm-feed-card.vue');
    module.exports = {
        data:function(){
            return{
                producer_info: BLADE.producer,
                feeds: BLADE.feed,
                reward: BLADE.reward,
                is_follow: BLADE.is_follow,
                is_producer: BLADE.is_producer,
                tag_post: BLADE.tag_post,
                loading: 0,
                cur_page: 1,
                has_more: 1,
                per_page: 5,
            }
        },
        components:{
            'vm-feed-card': vmFeedCard,
        },
        created:function(){
            let _this = this;
            function getTimeline(data){
                let scrollTop = $(data).scrollTop();
                let scrollHeight = $(document).height();
                let windowHeight = $(data).height();
                if(_this.has_more && scrollTop + windowHeight + 200 >= scrollHeight){
                    if(!_this.loading){
                        _this.loading = 1;
                        ++_this.cur_page;
                        $.get('/producer/get/timeline', {cur_page: _this.cur_page, url_slug: _this.producer_info['url_slug']}, function(data){
                            if(data.ec == 200){
                                $.each(data.data.feed,function(k,v){
                                    _this.feeds.push(v);
                                });
                                _this.has_more = data.data.has_more;
                            }else{
                                //失败操作
                            }
                            _this.loading = 0;
                        });
                    }
                }
            }
            getTimeline(window);
            $(window).on('scroll',function(){
                getTimeline(this)
            })
        },
        methods:{
            method_follow:function(){
                var _this = this;
                $.post('/post/follow', { producer_id: _this.producer_info['id'] }, function(data){
                    if(data.ec == 200){
                        alertify.success(data.em);
                        _this.is_follow = 1;
                    }else{
                        alertify.error(data.em);
                    }
                })
            },
            method_follow_remove:function(){
                var _this = this;
                $.post('/post/remove-follow', { producer_id: _this.producer_info['id'] }, function(data){
                    if(data.ec == 200){
                        alertify.success(data.em);
                        _this.is_follow = 0;
                    }else{
                        alertify.error(data.em);
                    }
                })
            }
        }
    }
</script>