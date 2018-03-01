<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PostDraft
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
 * @property int $post_id
 * @property string $create_time    为草稿准备的
 * @property string $publish_time
 * @property string $update_time
 *
 */

class PostDraft extends Model
{
    //
    protected $table = 'post_draft';

    public $timestamps = false;




    public static function addDraft($producer_id, $title, $public_content, $content,$type = 1, $limit_grade = 0, $post_id = null){
        $model = new PostDraft();
        $time = time();
        $model->producer_id = $producer_id;
        $model->title = $title;
        $model->public_content = $public_content;
        $model->content = $content;
        $model->status = 2;
        $model->type = $type;
        $model->limit_grade = $limit_grade;
        $model->post_id = $post_id;
        $model->create_time = $time;
        $model->publish_time = $time;
        $model->update_time = $time;
        $model->save();

        $model->toArray();
        return $model;
    }

    public static function updateDraft($producer_id, $id, $title, $public_content, $content, $type = 1, $limit_grade = 0){
        $model = PostDraft::where('id', $id)->first();
        $time = time();
        $model->title = $title;
        $model->public_content = $public_content;
        $model->content = $content;
        $model->limit_grade = $limit_grade;
        $model->update_time = $time;
        $model->save();

        $model->toArray();
        return $model;
    }

    public static function removeDraftById($id){
        $model = PostDraft::where('id', $id)->delete();
    }

    public static function getDraft($producer_id){
        $model = PostDraft::where('producer_id', $producer_id)
            ->get();
        return $model;
    }

    public static function getDraftById($id){
        $model = PostDraft::where('id', $id)
            ->first();
        return $model;
    }
}
