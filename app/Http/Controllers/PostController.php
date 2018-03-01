<?php

namespace App\Http\Controllers;

use App\Model\PostDraft;
use App\Model\TagPostRela;
use App\Model\UserCheck;
use Illuminate\Http\Request;
use App\Model\Producer;
use App\Model\Post;
use App\Libraries\Response;
use App\Model\TagPost;

class PostController extends Controller
{
    //
    public function index(){
        $user = UserCheck::getUserArray();
        if($user['role'] == 3) {
            $user['producer_info'] = Producer::getProducerByUserid($user['id']);
        }
//        $user['role'] == 3 发起者
//        若角色不为发起者，重定向到个人页，前端记得也要隐藏入口
        if($user['role'] != 3){
            return redirect('/home');
        }else{
            $producer = Producer::getProducer($user['id']);
            $tag_post = TagPost::getTagPost($producer['id']);

            $data = [
                'user' => $user,
                'producer' => $producer,
                'tag_post' => $tag_post,
            ];
            return view('post',$data);
//            return $data;
        }
    }

    public function getDraftList(){
        $user_id = UserCheck::getUserId();
        $producer = Producer::getProducerByUserid($user_id);
        $model = PostDraft::getDraft($producer['id']);
        foreach($model as $k){
            $k['rela_tags'] = TagPostRela::getTagPostRela($k['id'], $producer['id'], 2);
        }
        return Response::formatJson(200, '', $model);
    }

    public function getPostedList(){
        $user_id = UserCheck::getUserId();
        $producer = Producer::getProducerByUserid($user_id);
        $model = Post::getPostByProducerid($producer['id']);
        foreach($model as $k){
//            加关联标签
            $k['rela_tags'] = TagPostRela::getTagPostRela($k['id'], $producer['id'], 1);

//            查询是否有草稿，有则带上
            if($k['draft_id']){
                $k['draft_info'] = PostDraft::getDraftById($k['draft_id']);
                $k['draft_info']['rela_tags'] = TagPostRela::getTagPostRela($k['draft_info']['id'], $producer['id'], 2);
            }
        }

        return Response::formatJson(200, '', $model);
    }

    public function postNew(){
        $user_id = UserCheck::getUserId();
        $producer = Producer::getProducerByUserid($user_id);
        $post_draft_id = \Request::input('draft_id','');
        $title = \Request::input('title','');
        $public_content = \Request::input('public_content', '');
        $content = \Request::input('content','');
        $limit_grade = \Request::input('limit_grade', 0);
        $rela_tags = \Request::input('rela_tags', []);

        if(!$post_draft_id){
            //直接发布，不必处理草稿
            $model = Post::addPost($producer['id'], $title, $public_content, $content, 1, $limit_grade);
            if(count($rela_tags)){
                TagPostRela::addTagPostRela($model['id'], $producer['id'], 1, $rela_tags);
            }
        }else{
            //发布之前先对草稿进行处理
            $model = Post::addPost($producer['id'], $title, $public_content, $content, 1, $limit_grade);
            if(count($rela_tags)){
                TagPostRela::addTagPostRela($model['id'], $producer['id'], 1, $rela_tags);
            }
            //处理标签
            TagPostRela::removeTagPostRela($post_draft_id, $producer['id'], 2, TagPostRela::getTagPostRela($post_draft_id, $producer['id'], 2));
            //处理草稿
            PostDraft::removeDraftById($post_draft_id);
        }

        if(!$model){
//            存取内容失败
        }else{
            $model = $model->toArray();
            return Response::formatJson(200,'成功',$model);
        }
    }

    public function postModify(){
        $user_id = UserCheck::getUserId();
        $producer = Producer::getProducerByUserid($user_id);
        $post_draft_id = \Request::input('draft_id','');
        $title = \Request::input('title','');
        $public_content = \Request::input('public_content', '');
        $content = \Request::input('content','');
        $limit_grade = \Request::input('limit_grade', 0);
        $post_id = \Request::input('post_id', null);
        $rela_tags = \Request::input('rela_tags', []);

        if(!$post_draft_id){
//            没有草稿时，直接更新原post即可，有阅读权限变更时，要更新feed
            $post = Post::updatePost($post_id, $title, $public_content, $content, 1, $limit_grade);
        }else{
//            有草稿时，要对草稿进行处理，包括原草稿删除，原草稿关联标签删除和更新post的标签关联
            $post = Post::updatePost($post_id, $title, $public_content, $content, 1, $limit_grade);
//            处理标签
            $source_rela_tags = TagPostRela::getTagPostRela($post_id, $producer['id'], 1);
            $remove_arr = [];
            foreach($source_rela_tags as $v){
                if(!in_array($v, $rela_tags)){
                    $remove_arr[] = $v;
                }
            }
            TagPostRela::removeTagPostRela($post_id, $producer['id'], 1, $remove_arr);
            $source_rela_tags = TagPostRela::getTagPostRela($post_id, $producer['id'], 1);
            $add_arr = [];
            foreach($rela_tags as $v){
                if(!in_array($v, $source_rela_tags)){
                    $add_arr[] = $v;
                }
            }
            TagPostRela::addTagPostRela($post_id, $producer['id'], 1, $add_arr);
            TagPostRela::removeTagPostRela($post_draft_id, $producer['id'], 2, TagPostRela::getTagPostRela($post_draft_id, $producer['id'], 2));
//            处理草稿
            PostDraft::removeDraftById($post_draft_id);
        }
        return Response::formatJson(200, '发布成功', $post);
    }

    public function postUpdateDraft(){
        $user_id = UserCheck::getUserId();
        $producer = Producer::getProducerByUserid($user_id);
        $post_draft_id = \Request::input('draft_id','');
        $title = \Request::input('title','');
        $public_content = \Request::input('public_content', '');
        $content = \Request::input('content','');
        $limit_grade = \Request::input('limit_grade', 0);
        $post_id = \Request::input('post_id', null);
        $rela_tags = \Request::input('rela_tags', []);
        if(!$title){
            $title = '';
        }
        if(!$public_content){
            $public_content = '';
        }
        if(!$content){
            $content = '';
        }

        if(!$post_draft_id){
//            新增草稿
            $draft = PostDraft::addDraft($producer['id'], $title, $public_content, $content, 1, $limit_grade, $post_id);
            if(count($rela_tags)){
                TagPostRela::addTagPostRela($draft['id'], $producer['id'], 2, $rela_tags);
            }
//            若是已有发布内容的新增草稿，将新增草稿与已发布内容关联上
            if($post_id){
                Post::updateDraftidById($post_id, $draft['id']);
            }
        }else{
//            更新草稿
            $draft = PostDraft::updateDraft($producer['id'], $post_draft_id, $title, $public_content, $content, 1, $limit_grade);

//            更新关联tag
            //先获取之前关联的tags
            $source_rela_tags = TagPostRela::getTagPostRela($post_draft_id, $producer['id'], 2);
            //对照新的先删除旧的
            $remove_arr = [];
            foreach($source_rela_tags as $v){
                if(!in_array($v, $rela_tags)){
                    $remove_arr[] = $v;
                }
            }
            TagPostRela::removeTagPostRela($post_draft_id, $producer['id'], 2, $remove_arr);

            //删除后再新取一次
            $source_rela_tags = TagPostRela::getTagPostRela($post_draft_id, $producer['id'], 2);
            //对照新的进行增加
            $add_arr = [];
            foreach($rela_tags as $v){
                if(!in_array($v, $source_rela_tags)){
                    $add_arr[] = $v;
                }
            }
            TagPostRela::addTagPostRela($post_draft_id, $producer['id'], 2, $add_arr);


        }
        return Response::formatJson(200, '保存成功', $draft);
    }
}
