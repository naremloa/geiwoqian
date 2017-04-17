@extends('main')
@section('title')
    发起者个人页
@endsection
@section('link')
@endsection
@section('content')
    @include('nav')
    {{--<article style="width: 960px; margin: 0 auto; height: 500px;">--}}
        <article class="producer-detail">
            <vm-producer-detail></vm-producer-detail>
        </article>
    {{--</article>--}}
    <script>
        window.BLADE ={
            user: {!! json_encode($user) !!},
            producer: {!! json_encode($producer) !!},
            feed: {!! json_encode($feed) !!},
            is_follow: '{{$is_follow}}',
        }
    </script>
    <script src="{{ asset('/dist/entry/producer.js') }}"></script>
@endsection