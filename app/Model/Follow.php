<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\UserCheck;
use App\Model\Producer;
use App\Model\User;
use App\Jobs\Feed\BroadcastUpdate;

/**
 * Class Follow
 * @package App\Model
 *
 * @property int $user_id
 * @property int $user_follow_count
 * @property int $contribute_grade    成为参与者后，自动默认为关注。若是普通关注，则参与等级为0
 * @property int $producer_id
 * @property int $producer_follower_count
 * @property string $create_time
 *
 */

class Follow extends Model
{
    protected $table = 'follow';

    public $timestamps = false;

    //
    /**
     * 关注某个发起者
     *
     * @param null $user_id
     * @param null $user_follow_count
     * @param $contribute_greade
     * @param $producer_id
     * @param null $producer_follower_count
     * @param string $time
     * @return array
     */
    public static function addFollow($user_id = null, $user_follow_count = null, $contribute_grade, $producer_id, $producer_follower_count = null, $time = ''){
        $model = new Follow();
        //没有传用户相关信息时，默认操作者为当前登陆用户
        if(!$user_id && !$user_follow_count){
            $user = UserCheck::getUserArray();
            $user_id = $user['id'];
            $user_follow_count = $user['follow_count'];
        }
        if(!$producer_follower_count){
            $producer_follower_count = Producer::getProducer($producer_id)['follower_count'];
        }
        if($time === ''){
            $time = time();
        }
        $model->user_id = $user_id;
        $model->user_follow_count = $user_follow_count;
        $model->contribute_grade = $contribute_grade;
        $model->producer_id = $producer_id;
        $model->producer_follower_count = $producer_follower_count;
        $model->create_time = $time;
        $model->save();

        Producer::updateProducerFollowerCount($producer_id, 1);
        User::updateUserFollowcount($user_id, 1);

        dispatch(new BroadcastUpdate($user_id, $producer_id, 1));

        return $model->toArray();
    }

    public static function removeFollow($user_id = null, $producer_id){
        $model = Follow::where('producer_id',$producer_id)
            ->where('user_id',$user_id)
            ->delete();
        Producer::updateProducerFollowerCount($producer_id, -1);
        User::updateUserFollowcount($user_id, -1);

        dispatch(new BroadcastUpdate($user_id, $producer_id, -1));
        return $model;
    }

    public static function getFollowers($producer_id){
        $model = Follow::where('producer_id',$producer_id)
            ->select(['user_id'])
            ->get()
            ->toArray();
        return $model;
    }

    public static function isFollow($user_id, $producer_id){
        $model = Follow::where('producer_id',$producer_id)
            ->where('user_id',$user_id)
            ->first();
        if($model){
            return 1;
        }else{
            return 0;
        }
    }
}
