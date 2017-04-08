@extends('main')
@section('title')
    个人页
@endsection
@section('link')
@endsection
@section('content')
    @include('nav')
    <article style="width: 960px; margin: 0 auto; height: 500px;">
        <a class="cl-btn follow-btn" onselectstart="return false" data-producer-id="1">关注</a>
        <a class="cl-btn remove-follow-btn" onselectstart="return false" data-producer-id="1">取消关注</a>
    </article>
    <script>
        $(function(){
            $('.follow-btn').on('click',function(e){
                let $target = $(e.target);
                if(!$target.hasClass('follow-btn')){
                    $target = $target.parents('.follow-btn');
                }
                let producer_id = $target.attr('data-producer-id');
                $.post('/post/follow', { producer_id: producer_id }, function(data){
                    console.log(data);
                    if(data.ec == 200){
                        console.log('关注成功');
                    }
                })
            })
            $('.remove-follow-btn').on('click',function(e){
                let $target = $(e.target);
                if(!$target.hasClass('remove-follow-btn')){
                    $target = $target.parents('.remove-follow-btn');
                }
                let producer_id = $target.attr('data-producer-id');
                $.post('/post/remove-follow', { producer_id: producer_id }, function(data){
                    console.log(data);
                    if(data.ec == 200){
                        console.log('取消关注成功');
                    }
                })
            })
        })
    </script>
@endsection