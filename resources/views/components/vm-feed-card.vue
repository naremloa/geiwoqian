<style>
    .vm-feed-card{
        background: rgba(248, 248, 248, 1);
        border: 1px solid rgba(220, 220, 220, 1);
        padding: 20px;
        box-shadow: 0 1px 0 0 rgba(0, 0, 0, .2);
        box-sizing: border-box;
        margin-bottom: 10px;
        color: #5a5a68;
    }
    .vm-feed-card .card-content{
        line-height: 1.7;
        word-break: break-all;
    }
    .vm-feed-card .card-title{
        color: #2c2c41;
        font-weight: 400;
    }
</style>
<template>
    <section class="vm-feed-card">
        <div class="card-title fs20">{{data.title}}</div>
        <div class="card-content mt-10">{{data.content}}</div>
    </section>
</template>
<script>
    module.exports={
        props: ['data'],
        data:function(){
            return{

            }
        },
    }
</script>