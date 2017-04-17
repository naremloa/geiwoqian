@extends('main')
@section('title')
    个人页
@endsection
@section('link')
@endsection
@section('content')
    @include('nav')
    <style>
        .feed-card-box .feed-card {
            background: #2c2c41;
            padding: 20px;
            box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .2);
            box-sizing: border-box;
            margin-bottom: 20px;
            color: white;
            height: 500px;
        }
    </style>
    <article style="width: 960px; margin: 0 auto;" id="feed">
        {{--<a class="cl-btn follow-btn" onselectstart="return false" data-producer-id="1">关注</a>--}}
        {{--<a class="cl-btn remove-follow-btn" onselectstart="return false" data-producer-id="1">取消关注</a>--}}
        {{--<a class="cl-btn get-feed" onselectstart="return false">获取feed</a>--}}
        <section class="feed-card-box">
            @foreach($feed as $i => $k)
                <div class="feed-card">
                    <div class="card-title fs20">{{$k['title']}}</div>
                    <div class="card-content mt-10">{{$k['content']}}</div>
                </div>
            @endforeach
            <div class="loading hide fs32">
                稍等下
            </div>
        </section>
    </article>
    <script>
        {{--window.BLADE = {--}}
            {{--feed: {!! json_encode($feed) !!},--}}
        {{--};--}}
        {{--Vue.component('vm-feed-card',{--}}
            {{--props: ['feed'],--}}
            {{--template: ''--}}
        {{--});--}}
        {{--var feed = new Vue({--}}
            {{--el: '#feed',--}}
            {{--data:function(){--}}
                {{--return{--}}
                    {{--feed: BLADE.feed,--}}
                {{--}--}}
            {{--},--}}
        {{--});--}}
        var cur_page = 1;
        var has_more = 1;
        $(function(){
            $(document).on('scroll',function(){
                var contentHeight = $('html')[0].scrollHeight;
                var viewHeight = $('body').height();
                var scrollHeight = $('body').scrollTop();
                if(scrollHeight + viewHeight + 200 >= contentHeight){
                    if(has_more && $('.feed-card-box .loading').hasClass('hide')){
                        $('.feed-card-box .loading').removeClass('hide');
                        var data = {
                            cur_page: ++cur_page
                        };
                        $.get('/user/timeline', data, function(data){
                            if(data.ec == 200){
                                var html = '';
                                $.each(data.data.feed,function(k,v){
                                    html += template('feed-card-tpl',{data: v})
                                });
                                $('.feed-card-box .loading').before(html);
                                has_more = data.data.has_more;
                                $('.feed-card-box .loading').addClass('hide');
                            }
                        })
                    }
                }
            });

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
    <script id="feed-card-tpl" type="text/html">
        <div class="feed-card">
            <div class="card-title fs20"><%=data.title%></div>
            <div class="card-content mt-10"><%=data.content%></div>
        </div>
    </script>
@endsection