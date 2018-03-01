<style>
    .vm-producer-setting .cl-wrap .cl-wrap-left{
        width: 300px;
        position: fixed;
    }
    .vm-producer-setting .cl-wrap .cl-wrap-right{
        width: 640px;
        margin-left: 320px;
    }
    #fileupload{
        opacity: 0;
        position: absolute;
        width: 96px;
        left: 0;
        top: 0;
    }
    .vm-producer-setting .setting-cover .setting-cover-box{
        width: 100%;
        height: 121px;
        background-size: cover;
        background-position: center;
    }
</style>
<template>
    <section class="vm-producer-setting">
        <div class="cl-wrap flex-box">
            <div class="cl-wrap-left cl-card">
                <div class="fs20 mb-20">设置项</div>
                <div>设置奖励</div>
                <div class="mt-10">设置标签</div>
            </div>
            <div class="cl-wrap-right">
                <div class="setting-contribute-grade cl-card">
                    <p>设置奖励</p>
                    <div class="mt-10">
                        <p>现有奖励：</p>
                        <template v-if="reward.length" v-for="(data, index) in reward">
                            <div class="flex-box mt-10">
                                <p>参与等级：{{index}}</p>
                                <p class="ml-20">参与金额：￥{{data.reward_fund}} or more</p>
                                <p class="ml-20">奖励标题：{{data.reward_title}}</p>
                                <p class="ml-20">奖励描述：{{data.reward_description}}</p>
                            </div>
                        </template>
                        <div v-else>空空如也......</div>
                    </div>
                    <div class="mt-10 flex-box">
                        <input class="cl-input" placeholder="请输入参与金额" v-model="add_reward_fund">
                        <input class="cl-input ml-10" placeholder="请输入奖励标题" v-model="add_reward_title">
                        <input class="cl-input ml-10" placeholder="请输入奖励描述" v-model="add_reward_description">
                    </div>
                    <div class="mt-10 flex-box">
                        <div class="cl-btn add_reward_item" @click="method_add_reward">增加</div>
                    </div>
                </div>
                <div class="setting-tag cl-card">
                    <p>设置标签</p>
                    <div class="flex-box mt-10">
                        <div class="cl-tag mr-10" v-for="tag_post in tag_post">{{ tag_post.name }}（{{ tag_post.rela_post_count }}）</div>
                        <div class="add-more flex-box" :class="{ hide: hide_tag_add_more }">
                            <input v-model="new_tag_name">
                            <div class="cl-btn" @click="method_click_tag_add_more">确认</div>
                        </div>
                        <div class="cl-btn" :class="{ hide: !hide_tag_add_more }" @click="method_show_tag_add_more">添加</div>
                    </div>
                </div>
                <div class="setting-cover cl-card">
                    <p>设置头图</p>
                    <div class="mt-10 setting-cover-box" :style="{backgroundImage:'url(' + cover_url +')'}">
                    </div>
                    <div class="flex-box mt-10">
                        <div class="cl-btn relative">上传图片
                            <input type="file" name="file" id="fileupload" style="">
                        </div>
                        <div class="cl-btn ml-10">确定</div>
                    </div>
                </div>
                <div class="cl-card" style="height: 1000px"></div>
            </div>
        </div>
    </section>
</template>
<script>
    export default{
        data(){
            return{
                user: BLADE.user,
                producer: BLADE.producer,
                reward: BLADE.reward,
                tag_post: BLADE.tag_post,
                add_reward_fund: 0,
                add_reward_title: '',
                add_reward_description: '',
                hide_tag_add_more: 1,
                new_tag_name: '',
                cover_url: BLADE.producer.cover,
            }
        },
        mounted(){
            let _this = this;
            $('#fileupload').fileupload({
                url: '/post/img/upload',
                progressall: function (e, data){
                    // let progress = parseInt(data.loaded / data.total * 100, 10);
                },
                done: function(e, data){
                    _this.cover_url = data.result.data;
                    console.log(data.result);
                }
            })
        },
        methods:{
            method_show_tag_add_more(){
                let _this = this;
                _this.hide_tag_add_more = 0;
            },
            method_click_tag_add_more(){
                let _this = this;
                let tag_name = $.trim(_this.new_tag_name);
                if(tag_name === ''){
                    alertify.error('标签名字不能为空');
                    return false;
                }
                $.post('/post/producer/add-post-new-tag', { new_tag_name: tag_name }, function(data){
                    if(data.ec == 200){
                        alertify.success(data.em);
                        //插入到已有的tag列
                        _this.tag_post.push(data.data);
                        //收尾处理，重置输入框
                        _this.hide_tag_add_more = 1;
                        _this.new_tag_name = '';
                    }else{
                        alertify.error(data.em);
                    }
                })
            },
            method_add_reward(){
                let _this = this;
                let producer_id = _this.producer['id'];
                let reward_fund = _this.add_reward_fund;
                let reward_title = $.trim(_this.add_reward_title);
                let reward_description = $.trim(_this.add_reward_description);
                let data = {
                    producer_id: producer_id,
                    reward_fund: reward_fund,
                    reward_title: reward_title,
                    reward_description: reward_description,
                };
                $.post('/post/producer/add-reward', data, function(data){
                    if(data.ec == 200){
                        alertify.success('新增成功');
                        //todo
                        //将当前新增的推进旧有数组
//                        _this.reward.push([{
//                        }])
                    }else{
                        alertify.error(data.em);
                    }
                })
            }
        }
    }
</script>