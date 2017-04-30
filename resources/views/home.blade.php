@extends('main')
@section('title')
    个人页
@endsection
@section('link')
@endsection
@section('content')
    <style>
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
    <section class="nav">
        <vm-nav :user="user"></vm-nav>
    </section>
    <article class="feed flex-box" style="width: 960px; margin: 0 auto; padding-top: 70px;">
        <div class="cl-wrap-left">
            <div class="feed_head flex-box">
                <p class="on">全部</p>
                <p>部分</p>
                <p>部分</p>
                <p>部分</p>
                <p>部分</p>
            </div>
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
        </div>
        <div class="cl-wrap-right">
            <div class="cl-card">
                <p class="fs20">所有发起者</p>
                <div class="flex-box mt-10" v-for="producer in producer_all">
                    <a v-cloak style="display: block; color: white;" :href="'/producer/'+ producer.url_slug">
                        @{{producer.name}}
                    </a>
                </div>
            </div>
            <div class="cl-card">
                <input type="file" name="file" id="fileupload">
            </div>
        </div>

    </article>
    <script>
        window.BLADE = {
            feed: {!! json_encode($feed) !!},
            producer_all: {!! json_encode($producer_all) !!},
            user: {!! json_encode($user) !!},
        };
    </script>
    <script src="{{ asset('/dist/entry/home.js') }}"></script>
    <script src="{{ asset('/dist/entry/nav.js') }}"></script>
@endsection