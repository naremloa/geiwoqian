<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\UserFeed;
use Illuminate\Http\Request;
use App\Jobs\Feed\Broadcast;
use App\Model\Follow;
use App\Model\Producer;
use App\Model\Post;
use zgldh\QiniuStorage\QiniuStorage;

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
     * 队列中处理单条post任务的目标函数，通常用于提交新post后的相关feed分发
     *
     * @param $feed
     */
    public function broadcastFeed($feed){
        $follower_ids = Follow::getFollowers($feed['producer_id']);
        $model = UserFeed::addUserFeed($feed, $follower_ids);
    }

    /**
     * 队列中处理多条feed任务的目标函数，通常用于捐献和关注时更新相关producer的所有post到个人feed中
     *
     * @param $producer_id
     * @param $user_id
     */
    public function broadcastUpdateFeed($user_id, $producer_id, $operate_type){
        if($operate_type == 1){
            $feeds = Feed::where('producer_id',$producer_id)->get()->toArray();
            $model = UserFeed::addUserFeeds($feeds, $user_id, $producer_id);
        }else if($operate_type == -1){
            $model = UserFeed::removeUserFeeds($user_id, $producer_id);
        }

    }

    /**
     * @param $url_slug
     * @return mixed
     */
    public static function getFeedByUrlslug($url_slug){
        $producer_id = Producer::where('url_slug',$url_slug)->first()->id;

        $model = Feed::where('producer_id',$producer_id)
                    ->paginate(2)
                    ->setPath('/feed/{url_slug}')
                    ->toArray();
        return $model;
    }

    public static function getFeedByUserfeed($feed_ids, $cur_page = 1, $per_page = 5){
        $feeds = Feed::whereIn('id',$feed_ids)
            ->select(['post_id'])
            ->get()
            ->toArray();
        $post_ids = array();
        foreach($feeds as $k => $v){
            $post_ids[$k] = $v['post_id'];
        }
        return Post::getPostByFeed($post_ids, ($cur_page - 1) * $per_page, $per_page);
    }

    public static function getFeedByProducerid($producer_id, $cur_page = 1, $per_page = 5){
        $feeds = Feed::where('producer_id', $producer_id)
            ->select(['post_id'])
            ->get()
            ->toArray();
        $post_ids = array();
        foreach($feeds as $k => $v){
            $post_ids[$k] = $v['post_id'];
        }
        return Post::getPostByFeed($post_ids, ($cur_page - 1) * $per_page, $per_page);
    }
}
