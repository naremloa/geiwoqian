<style>
    .vm-producer-post .cl-wrap .cl-wrap-left{
        width: 300px;
        position: fixed;
    }
    .vm-producer-post .cl-wrap .cl-wrap-right{
        width: 640px;
        margin-left: 320px;
    }
    .vm-producer-post .cl-wrap .cl-wrap-right .post-head{
        border-bottom: 2px solid #A7B2B8;
        margin-bottom: 40px;
    }
    .vm-producer-post .cl-wrap .cl-wrap-right .post-new .post-new-public-show .simditor .simditor-body{
        min-height: 100px;
    }
    .vm-producer-post .cl-wrap .cl-wrap-right .post-head p{
        padding: 0 40px;
        text-align: center;
        cursor: pointer;
        position: relative;
        padding-bottom: 10px;
    }
    .vm-producer-post .cl-wrap .cl-wrap-right .post-head p:hover{
        font-weight: bold;
    }
    .vm-producer-post .cl-wrap .cl-wrap-right .post-head p.on:after{
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        height: 2px;
        width: 100%;
        background: black;
    }
    .vm-producer-post .cl-wrap .cl-wrap-right .post-new .post-new-title input{
        width: 100%;
        background: rgba(248, 248, 248, 1);
        margin-bottom: 20px;
        font-size: 32px;
    }
    .vm-producer-post .cl-wrap .cl-wrap-right .post-new .post-new-public-show .public-show-head,
    .vm-producer-post .cl-wrap .cl-wrap-right .post-new .post-new-content .content-head{
        padding: 10px;
        background: #ccc;
    }
</style>
<template>
    <section class="vm-producer-post">
        <div class="cl-wrap flex-box">
            <div class="cl-wrap-left cl-card" style="margin-top: 66px;">
                <div class="cl-btn" style="text-align: center;" @click="method_click_submit">发布</div>
                <div class="flex-box mt-10">
                    <div class="cl-btn" @click="method_click_save">保存</div>
                </div>
                <div class="tag-box flex-box mt-10">
                    <div class="cl-tag mr-10" :class="{ on: tag.is_on }" v-for="tag in tag_posts" @click="method_click_on_tag_post($event)">{{ tag.name }}</div>
                </div>
            </div>
            <div class="cl-wrap-right">
                <div class="post-head flex-box">
                    <p class="flex-item-1" :class="{ on: postpage_page == 0 }" @click="method_click_page_new">新发布内容</p>
                    <p class="flex-item-1" :class="{ on: postpage_page == 1 }" @click="method_click_page_posted">已发布内容</p>
                    <p class="flex-item-1" :class="{ on: postpage_page == 2 }" @click="method_click_page_draft">草稿</p>
                </div>
                <div class="post-new" v-show="post_editor_show">
                    <div class="cl-card">
                        <div class="post-new-title">
                            <input v-model="post_title" placeholder="请填写发布内容标题">
                        </div>
                        <div class="post-new-public-show">
                            <div class="public-show-head flex-box pointer" @click="method_hide_post_new_public_show">
                                <p class="flex-item-1">请填写对外公开展示部分（非必要）</p>
                                <i class="fa fa-chevron-down"></i>
                            </div>
                            <div class="public-show-box" :class="{ hide: hide_post_new_public_show }">
                                <textarea class="sim-editor" placeholder="请填写对外公开展示部分（非必要）"></textarea>
                            </div>
                        </div>
                        <div class="post-new-content mt-10">
                            <div class="content-head flex-box pointer" @click="method_hide_post_new_content">
                                <div class="flex-item-1">请填写发布内容（必要）</div>
                                <i class="fa fa-chevron-down"></i>
                            </div>
                            <div class="content-box" :class="{ hide: hide_post_new_content }">
                                <textarea class="sim-editor" placeholder="Balabala"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="post-list" v-show="!post_editor_show">
                    <div class="cl-card" v-for="data in data_list">
                        <div class="list-title">{{ data.title }}</div>
                        <div class="list-public-content" v-html="data.public_content"></div>
                        <div class="list-content" v-html="data.content"></div>
                        <div class="flex-box">
                            <div class="flex-item-1"></div>
                            <div class="pointer" @click="method_click_edit_draft($event)">编辑</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
    export default{
        data(){
            return{
                user: BLADE.user,
                producer: BLADE.producer,

                tag_posts: [],
                postpage_page: 0,
                hide_post_new_public_show: 0,
                hide_post_new_content: 1,
                post_editor_show: 1,
                editor_public_content: null,
                editor_content: null,

                post_draft_id: '',           //草稿id
                post_id: '',                 //对已发布内容，将其草稿与之关联起来
                post_title: '',
                post_public_content: '',
                post_content: '',
                post_limit_grade: 0,

                data_list: [],
            }
        },
        computed:{
            post_rela_tags(){
                let _this = this;
                let arr = [];
                $.each(_this.tag_posts, function(k, v){
                    if(v.is_on == 1){
                        arr.push(v.id);
                    }
                });
                return arr;
            }
        },
        mounted(){
            let _this = this;
            let $editor_1 = new Simditor({
                textarea: $('.post-new-public-show .sim-editor'),
                toolbarHidden: true,
            });
            $editor_1.on('valuechanged', function(){
               _this.post_public_content = $editor_1.sync();
            });
            _this.editor_public_content = $editor_1;

            let $editor_2 = new Simditor({
                textarea: $('.post-new-content .sim-editor'),
                toolbarFloatOffset: 50,
            });
            $editor_2.on('valuechanged', function(){
                _this.post_content = $editor_2.sync();
            });
            _this.editor_content = $editor_2;

            $.each(BLADE.tag_post,function(k, v){
                _this.tag_posts.push({
                    id: v.id,
                    name: v.name,
                    is_on: 0,
                });
            });

        },
        methods:{
            method_hide_post_new_public_show(){
                this.hide_post_new_public_show = !this.hide_post_new_public_show;
            },
            method_hide_post_new_content(){
                this.hide_post_new_content = !this.hide_post_new_content;
            },
            method_click_on_tag_post(event){
                let _this = this;
                let $target = $(event.target);
                if(!$target.hasClass('cl-tag')){
                    $target = $target.parents('.cl-tag');
                }
                let index_num = $target.index();
                if(_this.tag_posts[index_num].is_on == 1){
                    _this.tag_posts[index_num].is_on = 0;
                }else{
                    _this.tag_posts[index_num].is_on = 1;
                }
            },
            method_click_save(){
                let _this = this;
                let data = {
                    draft_id: _this.post_draft_id,
                    title: _this.post_title,
                    public_content: _this.post_public_content,
                    content: _this.post_content,
                    limit_grade: _this.post_limit_grade,
                    post_id: _this.post_id,
                    rela_tags: _this.post_rela_tags,
                };
                $.post('/post/draft/update', data, function(data){
                    if(data.ec == 200){
                        alertify.success('保存成功');
                        _this.post_draft_id = data.data.id;
                    }else{
                        alertify.error(data.em);
                    }
                })
            },
            method_click_submit(){
                let _this = this;
                let data = {
                    draft_id: _this.post_draft_id,
                    title: _this.post_title,
                    public_content: _this.post_public_content,
                    content: _this.post_content,
                    limit_grade: _this.post_limit_grade,
                    post_id: _this.post_id,
                    rela_tags: _this.post_rela_tags,
                };
                let url = '';
                if(_this.post_id === ''){
                    url = '/post/postsubmit/new';
                }else{
                    url = '/post/postsubmit/modify';
                }
                $.post(url, data, function(data){
                    if(data.ec == 200){
                        alertify.success('提交成功');
                    }else{
                        alertify.error(data.em);
                    }
                })

            },
            method_click_page_new(){
                let _this = this;
                _this.data_list = [];
                //编辑器初始化
                _this.postpage_page = 0;
                _this.post_draft_id = '';
                _this.post_title = '';
                _this.post_public_content = '';
                _this.editor_public_content.setValue(_this.post_public_content);
                _this.post_content = '';
                _this.editor_content.setValue(_this.post_content);
                _this.post_limit_grade = '0';
                $.each(_this.tag_posts, function(k, v){
                    v.is_on = 0;
                });
                _this.post_editor_show = 1;
            },
            method_click_page_posted(){
                let _this = this;
                _this.post_editor_show = 0;
                _this.data_list = [];
                _this.postpage_page = 1;
                $.get('/postpage/posted-list', function(data){
                    if(data.ec == 200){
                        _this.data_list = data.data;
                    }else{
                        alertify.error(data.em);
                    }
                })

            },
            method_click_page_draft(){
                let _this = this;
                _this.post_editor_show = 0;
                _this.data_list = [];
                _this.postpage_page = 2;
                $.get('/postpage/draft-list', function(data){
                    if(data.ec == 200){
                        _this.data_list = data.data;
                    }else{
                        alertify.error(data.em);
                    }
                });
            },
            method_click_edit_draft(event){
                let _this = this;
                let $target = $(event.target).parents('.cl-card');
                let index_num = $target.index();

                if(_this.data_list[index_num].status == 2){
//                    继续编辑草稿
                    _this.post_draft_id = _this.data_list[index_num].id;
                    _this.post_id = _this.data_list[index_num].post_id ? _this.data_list[index_num].post_id : '';
                    _this.post_title = _this.data_list[index_num].title;
                    _this.post_public_content = _this.data_list[index_num].public_content;
                    _this.editor_public_content.setValue(_this.post_public_content);
                    _this.post_content = _this.data_list[index_num].content;
                    _this.editor_content.setValue(_this.post_content);
                    _this.post_limit_grade = _this.data_list[index_num].limit_grade;
                    $.each(_this.tag_posts, function(k, v){
                        if($.inArray(v.id, _this.data_list[index_num].rela_tags) > -1){
                            v.is_on = 1;
                        }else{
                            v.is_on = 0;
                        }
                    });
                    _this.post_editor_show = 1;
                }else if(_this.data_list[index_num].status == 1){
//                    继续编辑已发布内容
                    if(_this.data_list[index_num].draft_id){
//                        若是已有草稿
                        _this.post_draft_id = _this.data_list[index_num].draft_id;
                        _this.post_id = _this.data_list[index_num].id;
                        _this.post_title = _this.data_list[index_num].draft_info.title;
                        _this.post_public_content = _this.data_list[index_num].draft_info.public_content;
                        _this.editor_public_content.setValue(_this.post_public_content);
                        _this.post_content = _this.data_list[index_num].draft_info.content;
                        _this.editor_content.setValue(_this.post_content);
                        _this.post_limit_grade = _this.data_list[index_num].draft_info.limit_grade;
                        $.each(_this.tag_posts, function(k, v){
                            if($.inArray(v.id, _this.data_list[index_num].draft_info.rela_tags) > -1){
                                v.is_on = 1;
                            }else{
                                v.is_on = 0;
                            }
                        });
                    }else{
//                        若没有草稿
                        _this.post_draft_id = '';
                        _this.post_id = _this.data_list[index_num].id;
                        _this.post_title = _this.data_list[index_num].title;
                        _this.post_public_content = _this.data_list[index_num].public_content;
                        _this.editor_public_content.setValue(_this.post_public_content);
                        _this.post_content = _this.data_list[index_num].content;
                        _this.editor_content.setValue(_this.post_content);
                        _this.post_limit_grade = _this.data_list[index_num].limit_grade;
                        $.each(_this.tag_posts, function(k, v){
                            if($.inArray(v.id, _this.data_list[index_num].rela_tags) > -1){
                                v.is_on = 1;
                            }else{
                                v.is_on = 0;
                            }
                        });
                    }

                    _this.post_editor_show = 1;

                }
            }
        }
    }
</script>