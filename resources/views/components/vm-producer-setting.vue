<style>
    .vm-producer-setting .cl-wrap .cl-wrap-left{
        width: 300px;
        position: fixed;
    }
    .vm-producer-setting .cl-wrap .cl-wrap-right{
        width: 640px;
        margin-left: 320px;
    }
</style>
<template>
    <section class="vm-producer-setting">
        <div class="cl-wrap flex-box">
            <div class="cl-wrap-left cl-card">
                <div class="fs20 mb-20">设置项</div>
                <div>设置奖励</div>
            </div>
            <div class="cl-wrap-right">
                <div class="cl-card">
                    <div class="setting-contribute-grade">
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
                add_reward_fund: 0,
                add_reward_title: '',
                add_reward_description: '',
            }
        },
        methods:{
            method_add_reward:function(){
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