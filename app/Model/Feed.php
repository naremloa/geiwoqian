<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\UserFeed;
use Illuminate\Http\Request;
use App\Jobs\Feed\Broadcast;

/**
 * Class Feed
 * @package App\Model
 *
 * @property int $id
 * @property int $producer_id    旧的还是publisher_id，改成新的字段，post所属发起人
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

    public function addFeed($publisher_id, $post_id, $type, $time){
        $model = Feed::where('publisher_id',$publisher_id)
            ->where('post_id',$post_id)
            ->first();
        if(!$model){
            $model = new Feed();
        }
        $model->publisher_id = $publisher_id;
        $model->post_id = $post_id;
        $model->type = $type;
        $model->status = 1;
        $model->create_time = $time;
        $model->update_time = $time;
        $model->save();

        dispatch(new Broadcast($model->toArray()));

    }

    public function broadcastFeed($feed){
        $model = UserFeed::addUserFeed($feed, 4);
    }
}
