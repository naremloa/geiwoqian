<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\UserFeed;
use Illuminate\Http\Request;
use App\Jobs\Feed\Broadcast;
use App\Model\Follow;
use App\Model\Producer;

/**
 * Class Feed
 * @package App\Model
 *
 * @property int $id
 * @property int $producer_id
 * @property int $post_id
 * @property int $type    post类型，具体还没定下
 * @property int $limit_grade    post阅读权限，跟着producer自己定的等级跑的，0为公开，1往后则是相应权限
 * @property int $status
 * @property string $create_time
 * @property string $update_time
 *
 */
class Feed extends Model
{
    //
    protected $table = 'feed';

    public $timestamps = false;

    public function addFeed($producer_id, $post_id, $type, $limit_grade, $time){
        $model = Feed::where('producer_id',$producer_id)
            ->where('post_id',$post_id)
            ->first();
        if(!$model){
            $model = new Feed();
        }
        $model->producer_id = $producer_id;
        $model->post_id = $post_id;
        $model->type = $type;
        $model->limit_grade = $limit_grade;
        $model->status = 1;
        $model->create_time = $time;
        $model->update_time = $time;
        $model->save();

        dispatch(new Broadcast($model->toArray()));

    }

    /**
     * 这是队列中的任务调动的函数，处理feed分发
     *
     * @param $feed
     */
    public function broadcastFeed($feed){
        $follower_ids = Follow::getFollower($feed['producer_id']);
        $model = UserFeed::addUserFeed($feed, $follower_ids);
    }

    public static function getFeedByUrlslug($url_slug){
        $producer_id = Producer::where('url_slug',$url_slug)->first()->id;

        $model = Feed::where('producer_id',$producer_id)
                    ->paginate(2)
                    ->setPath('/feed/{url_slug}')
                    ->toArray();
        return $model;
    }
}
