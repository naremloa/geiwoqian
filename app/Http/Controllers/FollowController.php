<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Response;
use App\Model\Follow;
use App\Model\Producer;
use App\Model\UserCheck;
use App\Model\Contribute;

class FollowController extends Controller
{
    //
    public static function postFollow(){
        $user = UserCheck::getUserArray();
        $producer_id = \Request::input('producer_id');
//        普通的关注，参与等级为0，若是有实际参与，会覆盖掉
        $contribute_grade = 0;
        $time = time();
        if(!$producer_id){
            return Response::formatJson(404,'请关注有效的发起者',$producer_id);
        }
        if($user['id'] == $producer_id){
            return Response::formatJson(500,'请不要关注你自己','');
        }
        $producer = Producer::getProducer($producer_id);
        if(!$producer){
            return Response::formatJson(404,'请关注有效的发起者',$producer);
        }

        $model = Follow::addFollow($user['id'],$user['follow_count'],$contribute_grade,$producer['id'],$producer['follower_count'],$time);
        return Response::formatJson(200,'关注成功',$model);
    }

    public static function postRemoveFollow(){
        $user = UserCheck::getUserArray();
        $producer_id = \Request::input('producer_id');
        $user_contribute_grade = Contribute::getUserContributeGrade($user['id'], $producer_id);
        //若对当前操作发起者还是处于参与者状态，不能取消关注
        if($user_contribute_grade === 0){
            return Response::formatJson(400, '你还在支持他，不能取消关注', []);
        }else{
            $model = Follow::removeFollow($user['id'], $producer_id);
            return Response::formatJson(200, '取消关注成功', $model);
        }
    }
}
