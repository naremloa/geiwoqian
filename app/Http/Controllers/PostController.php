<?php

namespace App\Http\Controllers;

use App\Model\UserCheck;
use Illuminate\Http\Request;
use App\Model\Producer;

class PostController extends Controller
{
    //
    public static function index(){
        $user = UserCheck::getUserArray();
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
}
