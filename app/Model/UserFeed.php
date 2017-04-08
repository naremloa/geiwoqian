<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserFeed
 * @package App\Model
 *
 * @property int $id
 * @property int $producer_id        post所属发起人id
 * @property int $user_id            分发到的用户id
 * @property int $feed_id            post本身id
 * @property int $limit_grade        post阅读权限
 * @property int $create_time
 * @property int $update_time
 *
 */
class UserFeed extends Model
{
    //
    protected $table = 'user_feed';

    public $timestamps = false;

    /**
     * @param $feed
     * @param array $follower_ids
     */
    public static function addUserFeed($feed, $follower_ids){
        foreach($follower_ids as $follower_id){
            $model = new UserFeed();
            $time = time();
            $model->producer_id = $feed['producer_id'];
            $model->user_id = $follower_id['user_id'];
            $model->feed_id = $feed['id'];
            $model->limit_grade = $feed['limit_grade'];
            $model->create_time = $time;
            $model->update_time = $time;
            $model->save();
        }
    }

    public static function addUserFeeds($feeds, $user_id, $producer_id){
        UserFeed::removeUserFeeds($user_id, $producer_id);
        foreach($feeds as $feed){
            $model = new UserFeed();
            $time = time();
            $model->producer_id = $feed['producer_id'];
            $model->user_id = $user_id;
            $model->feed_id = $feed['id'];
            $model->limit_grade = $feed['limit_grade'];
            $model->create_time = $time;
            $model->update_time = $time;
            $model->save();
        }
    }

    public static function removeUserFeeds($user_id, $producer_id){
        UserFeed::where('producer_id',$producer_id)
            ->where('user_id', $user_id)
            ->delete();
    }
}
