<?php

namespace App\Http\Controllers;

use App\Libraries\Response;
use Illuminate\Http\Request;
use App\Model\UserCheck;
use App\Model\Producer;
use App\Model\Reward;
use App\Model\TagPost;

class ProducerSettingController extends Controller
{
    //
    public function index(){
        $user = UserCheck::getUserArray();
        if($user['role'] == 3) {
            $user['producer_info'] = Producer::getProducerByUserid($user['id']);
        }
        $producer = Producer::getProducerByUserid($user['id']);
        $reward = Reward::getReward($producer['id']);
        $tag_post = TagPost::getTagPost($producer['id']);
        $data = [
            'user' => $user,
            'producer' => $producer,
            'reward' => $reward,
            'tag_post' => $tag_post,
        ];
        return view('producer_setting',$data);
//        return $data;
    }

    public function postAddReward(){
        $producer_id = \Request::input('producer_id', null);
        $reward_fund = is_numeric(\Request::input('reward_fund')) ? \Request::input('reward_fund') * 1 : null;
        $reward_title = trim(\Request::input('reward_title'));
        $reward_description = trim(\Request::input('reward_description'));

        if (!$producer_id) {
            return Response::formatJson(404, '请传入有效发起者信息', []);
        }

        if (!$reward_fund) {
            return Response::formatJson(500, '请输入有效金额', []);
        }

        $model = Reward::addReward($producer_id, $reward_fund, $reward_title, $reward_description);

        if(!$model){

        }

        return Response::formatJson(200, '成功', $model);


    }
}
