@extends('main')
@section('title')
    发起者设置页
@endsection
@section('link')
@endsection
@section('content')
    @include('nav')
    <article class="producer-setting" style="padding-top: 70px;">
        <vm-producer-setting></vm-producer-setting>
    </article>
    <script>
        window.BLADE ={
            user: {!! json_encode($user) !!},
            producer: {!! json_encode($producer) !!},
            reward: {!! json_encode($reward) !!},
            tag_post: {!! json_encode($tag_post) !!},
        }
    </script>
    <script src="{{ asset('/dist/entry/producer_setting.js') }}"></script>
@endsection