<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Feed;

/**
 * Class Post
 * @package App\Model
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property int $status
 * @property int $type
 * @property int $limit_grade    post阅读权限，跟着producer自己定的等级跑的，0为公开，1往后则是相应权限
 * @property string $create_time
 * @property string $publish_time
 * @property string $update_time
 *
 */

class Post extends Model
{
    //
    protected $table = 'post';

    public $timestamps = false;


    public static function addPost($user_id, $title, $content){
        $model = new Post();
        $time = time();
        $model->user_id = $user_id;
        $model->title = $title;
        $model->content = $content;
        $model->status = 1;
        $model->type = 1;
        $model->create_time = $time;
        $model->publish_time = $time;
        $model->update_time = $time;
        $model->save();

        $feed = new Feed();
        $feed->addFeed($model->user_id, $model->id, $model->type, $time);

        return $model;
    }


}
