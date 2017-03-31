<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Follow
 * @package App\Model
 *
 * @property int $user_id
 * @property int $user_follow_count
 * @property int $contribute_grade
 * @property int $producer_id
 * @property int $producer_follower_count
 * @property string $create_time
 *
 */

class Follow extends Model
{
    //
    public static function setFollow($user_id, $contribute_greade, $producer_id, $producer_follower_count){
        $model = new Follow();
        $time = time();
        $model->user_id = $user_id;
        $model->contribute_grade = $contribute_greade;
        $model->producer_id = $producer_id;
        $model->producer_follower_count = $producer_follower_count;
        $model->create_time = $time;
        $model->save();

        return $model->toArray();
    }
}
