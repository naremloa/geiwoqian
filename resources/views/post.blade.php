@extends('main')
@section('title')
    发起者发布页
@endsection
@section('link')
@endsection
@section('content')
    @include('nav')
    <article style="width: 960px; margin: 0 auto; height: 500px;">
        <div class="post-box" style="text-align: center; height: auto; box-sizing: border-box;">
            <a class="cl-btn post-start-btn" onselectstart="return false">发布文字</a>
            <a class="cl-btn post-cancel-btn hide" onselectstart="return false">取消</a>
        </div>
        <div class="post-area hide">
            <p>标题：</p>
            <input class="cl-input" name="title" style="width: 100%;" autocomplete="off">
            <p>正文：</p>
            <div class="text-div" contenteditable="true" name="content"></div>
            <div class="submit-box mt-40" style="text-align: center;">
                <a class="cl-btn submit-btn" onselectstart="return false">提交</a>
            </div>
        </div>
    </article>
    <section id="box">
        <vm-test></vm-test>
    </section>
    <script src="{{ asset('/dist/entry/post.js') }}"></script>
    <script>
        $(function(){
            $('.post-box .post-start-btn').on('click',function(e){
                $target = $(e.target);
                if(!$target.hasClass('post-start-btn')){
                    $target = $target.parents('.post-start-btn');
                }
                $box = $target.parents('.post-box');
                if(!$target.hasClass('hide')){
                    $target.addClass('hide');
                    $target.siblings('.post-cancel-btn').removeClass('hide');
                    $box.siblings('.post-area').removeClass('hide');
                }
            });
            $('.post-box .post-cancel-btn').on('click',function(e){
                $target = $(e.target);
                if(!$target.hasClass('post-cancel-btn')){
                    $target = $target.parents('.post-cancel-btn');
                }
                $box = $target.parents('.post-box');
                if(!$target.hasClass('hide')){
                    $target.addClass('hide');
                    $target.siblings('.post-start-btn').removeClass('hide');
                    $box.siblings('.post-area').addClass('hide');
                }
            });
            $('.post-area .submit-box .submit-btn').on('click',function(e){
                $target = $(e.target);
                if(!$target.hasClass('submit-btn')){
                    $target = $target.parents('.submit-btn');
                }
                $box = $target.parents('.post-area');
                var title = $.trim($box.find('[name=title]').val());
                var content = $.trim($box.find('[name=content]').html());
                $data = {
                    title: title,
                    content: content,
                };
                console.log($data);
                $.post('/post/submit/new',$data,function(data){
                    console.log(data);
                })
            })
        })
    </script>
@endsection