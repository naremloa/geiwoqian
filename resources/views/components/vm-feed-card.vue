<style>
    .vm-feed-card{
        background: #2c2c41;
        padding: 20px;
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .2);
        box-sizing: border-box;
        margin-bottom: 20px;
        color: white;
    }
    .vm-feed-card .card-content{
        line-height: 1.7;
        word-break: break-all;
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