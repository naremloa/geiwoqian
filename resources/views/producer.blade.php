@extends('main')
@section('title')
    发起者个人页
@endsection
@section('link')
@endsection
@section('content')
    @include('nav')
    {{--<article style="width: 960px; margin: 0 auto; height: 500px;">--}}
        <article class="producer-detail" style="padding-top: 50px;">
            <vm-producer-detail></vm-producer-detail>
        </article>
    {{--</article>--}}
    <script>
        window.BLADE ={
            user: {!! json_encode($user) !!},
            producer: {!! json_encode($producer) !!},
            feed: {!! json_encode($feed) !!},
            reward: {!! json_encode($reward) !!},
            is_follow: '{{$is_follow}}',
            is_producer: '{{$is_producer}}',
            tag_post: {!! json_encode($tag_post) !!}
        }
    </script>
    <script src="{{ asset('/dist/entry/producer.js') }}"></script>
@endsection