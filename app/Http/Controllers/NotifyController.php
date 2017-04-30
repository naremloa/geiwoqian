<?php

namespace App\Http\Controllers;

use App\Libraries\Response;
use App\Model\User;
use Illuminate\Http\Request;
use App\Model\UserCheck;
use App\Model\Notify;

class NotifyController extends Controller
{
    //
    public function index(){

    }


    public function getCheck(){
        $user_id = UserCheck::getUserId();
        $origin_unread_num = \Request::input('origin_unread_num');
        $limit_num = 10;
        $unread_num = 0;
        $notify = Notify::getNotify($user_id, $limit_num);
        foreach($notify as $k => $v){
            if($v['status'] == 1){
                $unread_num++;
            }
        }
        $data = [
            'notify' => $notify,
            'notify_unread_num' => $unread_num,
        ];
        return Response::formatJson(200, '成功', $data);
    }
}
