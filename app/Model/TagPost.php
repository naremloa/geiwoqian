<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Producer;

/**
 * Class TagPost
 * @package App\Model
 *
 * @property int $id
 * @property string $name
 * @property int $producer_id
 * @property int $rela_post_count
 * @property string $create_time
 * @property string $update_time
 *
 */

class TagPost extends Model
{
    //
    protected $table = 'tag_post';

    public $timestamps = false;

    public static function addTagPost($tag_name, $producer_id){
        $time = time();
        $model = new TagPost();
        $model->name = $tag_name;
        $model->producer_id = $producer_id;
        $model->rela_post_count = 0;
        $model->create_time = $time;
        $model->update_time = $time;
        $model->save();

        //更新producer下的tag_post_count
        Producer::updateProducerTagPostCount($producer_id, 1);

        $model->toArray();
        return $model;
    }

    public static function getTagPost($producer_id){
        $model = TagPost::where('producer_id', $producer_id)
            ->get();
        return $model;
    }

    public static function updateRelaPostCount($producer_id, $tag_id, $operate_num){
        $model = TagPost::where('producer_id', $producer_id)
            ->where('id', $tag_id)
            ->first();
        $model->rela_post_count = $model->rela_post_count + $operate_num;
        $model->save();
        return $model->toArray();
    }

    public static function getTagPostByName($name, $producer_id){
        $model = TagPost::where('producer_id', $producer_id)
            ->where('name', $name)
            ->first();
        return $model;
    }
}
