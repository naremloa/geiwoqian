<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Feed;
use Symfony\Component\CssSelector\XPath\Extension\PseudoClassExtension;

/**
 * Class Post
 * @package App\Model
 *
 * @property int $id
 * @property int $producer_id
 * @property string $title
 * @property string $content
 * @property int $status
 * @property int $type
 * @property int $limit_grade    post阅读权限，跟着producer自己定的等级跑的，0为公开，1往后则是相应权限
 * @property string $create_time    为草稿准备的
 * @property string $publish_time
 * @property string $update_time
 *
 */

class Post extends Model
{
    //
    protected $table = 'post';

    public $timestamps = false;


    public static function addPost($producer_id, $title, $content,$type = 1, $limit_grade = 0){
        $model = new Post();
        $time = time();
        $model->producer_id = $producer_id;
        $model->title = $title;
        $model->content = $content;
        $model->status = 1;
        $model->type = $type;
        $model->limit_grade = $limit_grade;
        $model->create_time = $time;
        $model->publish_time = $time;
        $model->update_time = $time;
        $model->save();

        $feed = new Feed();
        $feed->addFeed($model->producer_id, $model->id, $model->type, $model->limit_grade, $time);

        return $model;
    }

    public static function getPostByFeed($post_ids, $offset_page = 0, $per_page = 5){
        $post = Post::whereIn('id', $post_ids)
            ->skip($offset_page)
            ->limit($per_page)
            ->get();
//            ->paginate(5)
//            ->setPath('/home');
        return $post;
    }

}
