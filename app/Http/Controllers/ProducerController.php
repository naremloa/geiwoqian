<?php

namespace App\Http\Controllers;

use App\Model\Feed;
use App\Model\Reward;
use App\Model\UserCheck;
use App\Model\Producer;
use App\Libraries\Response;
use App\Model\UserOperate;
use App\Model\Follow;
use App\Model\TagPost;


class ProducerController extends Controller
{
    public function index($url_slug){
        $user = UserCheck::getUserArray();
        $per_page = 5;
//        $user['role'] == 3 发起者
        if($user['role'] == 3) {
            $user['producer_info'] = Producer::getProducerByUserid($user['id']);
        }
        $producer = Producer::getProducerByUrlslug($url_slug);
        $is_follow = Follow::isFollow($user['id'], $producer['id']);
        $feed = Feed::getFeedByProducerid($producer['id']);
        $is_producer = Producer::isProducer($user['id'], $url_slug);
        $tag_post = TagPost::getTagPost($producer['id']);

        $reward = Reward::getReward($producer['id']);

        $data = [
            'user' => $user,
            'producer' => $producer,
            'feed' => $feed,
            'is_follow' => $is_follow,
            'has_more' => count($feed) == $per_page? 1: 0,
            'is_producer' => $is_producer,
            'reward' => $reward,
            'tag_post' => $tag_post,
        ];
        return view('producer',$data);
//            return $data;
    }

    public function getTimeline(){
        $user = UserCheck::getUserArray();
        $producer_url_slug = \Request::input('url_slug', '');
        $producer = Producer::getProducerByUrlslug($producer_url_slug);
        $cur_page = \Request::input('cur_page', 1);
        $per_page = 5;
        $feed = Feed::getFeedByProducerid($producer['id'], $cur_page, $per_page);
        $feed = Producer::getProducerInfoByFeed($feed);
        $data = [
            'feed' => $feed,
            'has_more' => count($feed) == $per_page? 1: 0,
        ];

        return Response::formatJson(200, '成功', $data);
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

    public function getEditReward($url_slug){
        $user = UserCheck::getUserArray();
//        $user['role'] == 3 发起者
        if($user['role'] == 3) {
            $user['producer_info'] = Producer::getProducerByUserid($user['id']);
        }
        $producer = Producer::getProducerByUrlslug($url_slug);

        if($producer['id'] === $user['producer_info']['id']){
            return redirect('/producer/'. $url_slug);
        }

        $reward = Reward::getReward($producer['id']);

        $data = [
            'user' => $user,
            'producer' => $producer,
            'reward' => $reward,
        ];
        return view('edit_reward', $data);
//        return $data;
    }
}
