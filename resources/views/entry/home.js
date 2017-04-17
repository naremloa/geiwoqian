import Vue from 'vue'
import vmTest from '../components/vm-test.vue'
import vmFeedCard from '../components/vm-feed-card.vue'

var VueRouter = require('vue-router')

Vue.use(VueRouter);

const Foo = { template: '<div>foo</div>' }
const Bar = { template: '<div>bar</div>' }

const routes = [
    { path: '/foo', component: Foo },
    { path: '/bar', component: Bar }
]
const router = new VueRouter({
    routes: routes,
})

const feed = new Vue({
    router: router,
    el: '.feed',
    data:function(){
        return{
            feeds: BLADE.feed,
            producer_all: BLADE.producer_all,
            loading: 0,
            cur_page: 1,
            has_more: 1,
            per_page: 5,
        }
    },
    components:{
        'vm-test': vmTest,
        'vm-feed-card': vmFeedCard,
    },
    created:function(){
        let _this = this;
        function getTimeline(data){
            let scrollTop = $(data).scrollTop();
            let scrollHeight = $(document).height();
            let windowHeight = $(data).height();
            if(_this.has_more && scrollTop + windowHeight + 200 >= scrollHeight){
                if(!_this.loading){
                    _this.loading = 1;
                    ++_this.cur_page;
                    $.get('/user/timeline', {cur_page: _this.cur_page}, function(data){
                        if(data.ec == 200){
                            $.each(data.data.feed,function(k,v){
                                _this.feeds.push(v);
                            });
                            _this.has_more = data.data.has_more;
                        }else{
                            //失败操作
                        }
                        _this.loading = 0;
                    });
                }
            }
        }
        getTimeline(window);
        $(window).on('scroll',function(){
            getTimeline(this);
        })
    },
});
console.log(feed);