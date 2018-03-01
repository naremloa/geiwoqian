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
 * @property string $public_content
 * @property string $content
 * @property int $status    草稿，status == 2  已发布，status == 1
 * @property int $type
 * @property int $limit_grade    post阅读权限，跟着producer自己定的等级跑的，0为公开，1往后则是相应权限
 * @property  int $draft_id
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


    public static function addPost($producer_id, $title, $public_content, $content, $type = 1, $limit_grade = 0, $draft_id = null){
        $model = new Post();
        $time = time();
        $model->producer_id = $producer_id;
        $model->title = $title;
        $model->public_content = $public_content;
        $model->content = $content;
        $model->status = 1;
        $model->type = $type;
        $model->limit_grade = $limit_grade;
        $model->draft_id = $draft_id;
        $model->create_time = $time;
        $model->publish_time = $time;
        $model->update_time = $time;
        $model->save();

        $feed = new Feed();
        $feed->addFeed($model->producer_id, $model->id, $model->type, $model->limit_grade, $time);

        return $model;
    }

    public static function $$getPostByFeed($post_ids, $offset_page = 0, $per_page = 5){
        $post = Post::whereIn('id', $post_ids)
            ->skip($offset_page)
            ->limit($per_page)
            ->get();
//            ->paginate(5)
//            ->setPath('/home');
        return $post;
    }

    public static function getPostByProducerid($producer_id){
        $model = Post::where('producer_id', $producer_id)
            ->orderBy('update_time', 'desc')
            ->get();
        return $model;
    }

    public static function updateDraftidById($post_id, $draft_id){
        $model = Post::where('id', $post_id)
            ->first();
        $model->draft_id = $draft_id;
        $model->save();

        return $model->toArray();
    }

    public static function updatePost($post_id, $title, $public_content, $content, $type = 1, $limit_grade = 0, $draft_id = null){
        $time = time();
        $model = Post::where('id', $post_id)
            ->first();
        $model->title = $title;
        $model->public_content = $public_content;
        $model->content = $content;
        $model->status = 1;
        $model->type = $type;
        $model->limit_grade = $limit_grade;
        $model->draft_id = $draft_id;
        $model->update_time = $time;
        $model->save();

        //阅读权限变更时，要更新feed
        //todo

        return $model->toArray();
    }

}
