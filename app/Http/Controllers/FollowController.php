<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Response;
use App\Model\Follow;
use App\Model\Producer;
use App\Model\UserCheck;

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
        $producer = Producer::getProducer($producer_id);
        if(!$producer){
            return Response::formatJson(404,'请关注有效的发起者',$producer);
        }

        $model = Follow::addFollow($user['id'],$user['follow_count'],$contribute_grade,$producer['id'],$producer['follower_count'],$time);
        return Response::formatJson(200,'关注成功',$model);
    }
}
