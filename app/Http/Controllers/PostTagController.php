<?php

namespace App\Http\Controllers;

use App\Libraries\Response;
use Illuminate\Http\Request;
use App\Model\TagPost;
use App\Model\TagPostRela;
use App\Model\UserCheck;
use App\Model\Producer;

class PostTagController extends Controller
{
    //
    public function addNewTag(){
        $user_id = UserCheck::getUserId();
        $producer = Producer::getProducerByUserid($user_id);
        $tag_name = trim(\Request::input('new_tag_name'));

        //检查已有tag名字，让名字具唯一性
        $check = TagPost::getTagPostByName($tag_name, $producer['id']);
        if($check){
            return Response::formatJson(404, '创建失败，已有同名标签', $check);
        }
        $tag = TagPost::addTagPost($tag_name, $producer['id']);
        if($tag){
            return Response::formatJson(200, '创建成功', $tag);
        }else{
            return Response::formatJson(500, '创建失败', []);
        }
    }
}
