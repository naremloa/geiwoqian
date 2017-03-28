<?php

namespace App\Http\Controllers;

use App\Model\UserCheck;
use App\Model\Producer;
use App\Libraries\Response;
use App\Model\UserOperate;


class ProducerController extends Controller
{
    public function index(){
        $user = UserCheck::getUserArray();
//        $user['role'] == 3 发起者
//        若角色不为发起者，重定向到个人页，前端记得也要隐藏入口
        if($user['role'] != 3){
            return redirect('/home');
        }else{
            Producer::getProducer($user['id']);

            $data = [
                'user' => $user,
            ];
            return view('producer',$data);
        }
    }

    public function postApply(){
        $user = UserCheck::getUserArray();
//        $user['role'] == 3 发起者
//        若角色已为发起者，返回报错，前端记得隐藏入口
        if($user['role'] == 3){
            return Response::formatJson(404, '你已经是发起者了');
        }else{
            $model = Producer::addProducer($user['id']);
            if(!$model){
                return Response::formatJson(404,'你已经是发起者了');
            }else{
                $return_infor = UserOperate::update2Producer($user['id']);
                //user表更新返回错误，删除producer表相关数据
                //表关联还未完成，删除操作先挂起
                //todo
                if(!$return_infor){
//                    Producer::deleteProducer($user['id']);
                    return Response::formatJson(500,'未能完成用户角色更新');
                }
            }
            $model = $model->toArray();
            return Response::formatJson(200,'成功',$model);

        }
    }
}
