<?php

namespace App\Http\Controllers;

use App\Model\UserCheck;
use Illuminate\Http\Request;
use App\Model\Producer;
use App\Model\Post;
use App\Libraries\Response;

class PostController extends Controller
{
    //
    public static function index(){
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

            $data = [
                'user' => $user,
                'producer' => $producer,
            ];
            return view('post',$data);
//            return $data;
        }
    }

    public static function postNew(){
        $user_id = UserCheck::getUserId();
        $title = \Request::input('title','');
        $content = \Request::input('content','');
        $model = Post::addPost($user_id,$title,$content);
        if(!$model){
//            存取内容失败
        }else{
            $model = $model->toArray();
            return Response::formatJson(200,'成功',$model);
        }


    }
}
