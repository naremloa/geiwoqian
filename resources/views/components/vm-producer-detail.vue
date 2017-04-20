<style>
    .vm-producer-detail .producer-head{
        height: 50px;
        background: rgba(44, 44, 65, 1);
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .2);
    }
    .vm-producer-detail .producer-head .producer-avatar{
        width: 100px;
        height: 100px;
        margin-top: -50px;
    }
    .vm-producer-detail .producer-head .producer-name{
        line-height: 50px;
    }
    .vm-producer-detail .producer-head .follow-btn{
        height: 30px;
        margin-top: 10px;
        line-height: 20px;
    }
    .vm-producer-detail .producer-head .contribute-btn{
        height: 30px;
        margin-top: 10px;
        line-height: 20px;
    }
    .vm-producer-detail .producer-body .head p{
        padding: 10px;
        margin: 0 0 10px 0;
        box-sizing: border-box;
        cursor: pointer;
    }
    .vm-producer-detail .producer-body .head p.on{
        background: #2c2c41;
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .2);
    }
    .vm-producer-detail .producer-body .producer-intro{
        lien-height: 1.7;
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
</style>
<template>
    <section class="vm-producer-detail">
        <div class="cl-bg" style="height: 300px; background-size: cover" :style="{backgroundImage:'url(' + producer_info['cover'] +')'}">
        </div>
        <div class="producer-head">
            <div class="cl-wrap flex-box">
                <div class="producer-avatar">
                    <img class="circle wh100" :src="producer_info['avatar']">
                </div>
                <div class="producer-name ml-20 fs20">{{producer_info['name']}}</div>
                <div class="flex-item-1"></div>
                <template v-if="is_follow == 1">
                    <div class="cl-btn follow-btn" onselectstart="return false" @click="method_follow_remove">取关</div>
                </template>
                <template v-else="is_follow == 0">
                    <div class="cl-btn follow-btn" onselectstart="return false" @click="method_follow">关注</div>
                </template>
                <a class="cl-btn contribute-btn ml-20" onselectstart="return false" :href="'/producer/'+producer_info.url_slug+'/edit-reward'">成为支持者</a>
            </div>
        </div>
        <div class="producer-body cl-wrap flex-box pt-40">
            <div class="cl-wrap-left">
                <template v-if="producer_info['intro'] === ''">
                    <div class="producer-intro cl-card">
                        <p>这是一块介绍面板</p>
                        <p>这是一块介绍面板</p>
                        <p>这是一块介绍面板</p>
                        <p>这是一块介绍面板</p>
                        <p>这是一块介绍面板</p>
                    </div>
                </template>
                <template v-else>
                    <div class="producer-intro cl-card">{{producer_info['intro']}}</div>
                </template>

                <div class="head flex-box">
                    <p class="on">全部</p>
                    <p>部分</p>
                    <p>部分</p>
                    <p>部分</p>
                    <p>部分</p>
                </div>
                <div class="content">
                    <vm-feed-card v-for="data in feeds" :data="data" :key="data.id"></vm-feed-card>
                </div>
            </div>
            <div class="cl-wrap-right">
                <div class="cl-card">
                    <div class="flex-box">
                        <div>
                            <p>支持者</p>
                            <p class="fs20">{{producer_info['backer_count']}}</p>
                        </div>
                        <div class="ml-40">
                            <p>被给</p>
                            <p class="fs20">￥{{producer_info['get_fund_per_month']}} / 月</p>
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