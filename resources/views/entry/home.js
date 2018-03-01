import Vue from 'vue'
import vmTest from '../components/vm-test.vue'
import vmFeedCard from '../components/vm-feed-card.vue'

var feed = new Vue({
    el: '.feed',
    data:function(){
        return{
            user: BLADE.user,
            feeds: BLADE.feed,
            producer_all: BLADE.producer_all,
            loading: 0,
            cur_page: 1,
            has_more: 1,
            per_page: 5,
        }
    },
    components:{
        vmTest,
        vmFeedCard,
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
    mounted:function(){
        let _this = this;
        $('#fileupload').fileupload({
            url: '/post/img/upload',
            progressall: function (e, data){
                // let progress = parseInt(data.loaded / data.total * 100, 10);
            },
            done: function(e, data){
                console.log(data);
            }
        })
    }
});