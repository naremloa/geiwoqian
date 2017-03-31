<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserFeed
 * @package App\Model
 *
 * @property int $id
 * @property int $producer_id
 * @property int $user_id
 * @property int $feed_id
 * @property int $limit_grade
 * @property int $create_time
 * @property int $update_time
 *
 */
class UserFeed extends Model
{
    //
    protected $table = 'user_feed';

    public $timestamps = false;

    public static function addUserFeed($feed, $follower_ids){
        $model = new UserFeed();
        $time = time();
        $model->producer_id = $feed['publisher_id'];
        $model->user_id = ;
        $model->feed_id = $feed['id'];
        $model->limit_grade = $feed['limit_grade'];
        $model->create_time = $time;
        $model->update_time = $time;
        $model->save();

        return $model;
    }
}
