<style>
    .vm-edit-reward .reward-card .cl-card{
        border: 2px solid rgba(0, 0, 0, 0);
    }
    .vm-edit-reward .reward-card .cl-card.on{
        border: 2px solid rgba(255, 66, 95, 1);
    }
    .vm-edit-reward .reward-card .cl-card .submit-area{
        display: none;
    }
    .vm-edit-reward .reward-card .cl-card.on .submit-area{
        display: block;
    }
</style>
<template>
    <section class="vm-edit-reward">
        <div class="cl-wrap flex-box">
            <div class="cl-wrap-left">
                <p class="fs20">{{producer.name}} 给大家带来的奖励</p>
                <div class="mt20 reward-card">
                    <template v-for="data in reward">
                        <div class="cl-card mt-20" style="cursor: pointer;" @click="method_choose_reward_card($event)">
                            <div class="flex-box">
                                <div class="cl-radio"></div>
                                <div class="ml-20">
                                    <div style="line-height: 16px;">￥{{data.reward_fund}} + </div>
                                    <div class="mt-20">{{data.reward_title}}</div>
                                    <div class="mt-10">{{data.reward_description}}</div>
                                    <div class="submit-area mt-20">
                                        <div class="flex-box">
                                            <input class="cl-input" style="width: 150px;" v-model="input_fund_per_month">
                                            <div class="cl-btn ml-20" @click="method_edit_contribute">支持</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="cl-wrap-right">

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
                input_fund_per_month: null,
            }
        },
        mounted(){
            function QueryString(param_q){
                let sValue=location.search.match(new RegExp("[\?\&]" + param_q + "=([^\&]*)(\&?)","i"));
                return sValue?sValue[1]:sValue;
            }
            let init_reward_card = QueryString('reward_card');
            console.log(init_reward_card);
            if(init_reward_card === null){
                return false;
            }
            $('.vm-edit-reward .reward-card .cl-card').eq(init_reward_card).trigger('click');
        },
        methods:{
            method_choose_reward_card(event){
                let $target = $(event.target);
                if(!$target.hasClass('cl-card')){
                    $target = $target.parents('.cl-card');
                }
                $target.parents('.reward-card').find('.cl-card').removeClass('on');
                if(!$target.hasClass('on')){
                    this.input_fund_per_month = this.reward[$target.index()].reward_fund;
                    $target.addClass('on');
                    $target.find('.submit-area input').focus();
                }
            },
            method_edit_contribute(){
                let data = {
                    producer_id: this.producer.id,
                    fund_per_month: this.input_fund_per_month * 1,
                };
                $.post('/post/contribute/edit', data, function(data){
                    if(data.ec == 200){
                        alertify.success('添加成功');
                        console.log(data);
                    }else{
                        alertify.error(data.em);
                    }
                })
            }
        }
    }
</script>