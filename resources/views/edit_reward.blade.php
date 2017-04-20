@extends('main')
@section('title')
    选择参与项页
@endsection
@section('link')
@endsection
@section('content')
    @include('nav')
    <article class="edit-reward" style="padding-top: 70px;">
        <vm-edit-reward class="mt-20"></vm-edit-reward>
    </article>
    <script>
        window.BLADE ={
            user: {!! json_encode($user) !!},
            producer: {!! json_encode($producer) !!},
            reward: {!! json_encode($reward) !!},
        }
    </script>
    <script src="{{ asset('/dist/entry/edit_reward.js') }}"></script>
@endsection