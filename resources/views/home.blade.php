@extends('main')
@section('title')
    个人页
@endsection
@section('link')
@endsection
@section('content')
    @include('nav')
    <style>
        .feed-card-box .vm-feed-card {
            background: #2c2c41;
            padding: 20px;
            box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .2);
            box-sizing: border-box;
            margin-bottom: 20px;
            color: white;
        }
        .feed .feed_head p{
            padding: 10px;
            margin: 0 0 10px 0;
            box-sizing: border-box;
            cursor: pointer;
        }
        .feed .feed_head p.on{
            background: #2c2c41;
            box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .2);

        }
    </style>
    <article class="feed" style="width: 960px; margin: 0 auto;">
        <div class="feed_head flex-box">
            <p class="on">全部</p>
            <p>部分</p>
            <p>部分</p>
            <p>部分</p>
            <p>部分</p>
        </div>
        {{--<a class="cl-btn follow-btn" onselectstart="return false" data-producer-id="1">关注</a>--}}
        {{--<a class="cl-btn remove-follow-btn" onselectstart="return false" data-producer-id="1">取消关注</a>--}}
        {{--<a class="cl-btn get-feed" onselectstart="return false">获取feed</a>--}}
        <section class="feed-card-box">
            <template v-if="feeds.length">
                <vm-feed-card v-for="data in feeds" :data="data"></vm-feed-card>
            </template>
            <template v-else>
                <section class="vm-feed-card">
                    空空如也......
                </section>
            </template>
            <div class="loading hide fs32">
                稍等下
            </div>
        </section>
    </article>
    <script>
        window.BLADE = {
            feed: {!! json_encode($feed) !!},
        };

        $(function(){

            $('.follow-btn').on('click',function(e){
                var $target = $(e.target);
                if(!$target.hasClass('follow-btn')){
                    $target = $target.parents('.follow-btn');
                }
                var producer_id = $target.attr('data-producer-id');
                $.post('/post/follow', { producer_id: producer_id }, function(data){
                    console.log(data);
                    if(data.ec == 200){
                        console.log('关注成功');
                    }
                })
            })
            $('.remove-follow-btn').on('click',function(e){
                var $target = $(e.target);
                if(!$target.hasClass('remove-follow-btn')){
                    $target = $target.parents('.remove-follow-btn');
                }
                var producer_id = $target.attr('data-producer-id');
                $.post('/post/remove-follow', { producer_id: producer_id }, function(data){
                    console.log(data);
                    if(data.ec == 200){
                        console.log('取消关注成功');
                    }
                })
            })
            $('.get-feed').on('click',function(e){
                var $target = $(e.target);
                var data = {
                    cur_page: 3,
                };

            })
        })
    </script>
    <script src="{{ asset('/dist/entry/home.js') }}"></script>
@endsection