<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TagPostRela
 * @package App\Model
 *
 * @property int $tag_post_id
 * @property int $post_id
 * @property int $producer_id
 * @property int $post_status
 * @property string $create_time
 *
 */

class TagPostRela extends Model
{
    //
    protected $table = 'tag_post_rela';

    public $timestamps = false;


    public static function addTagPostRela($post_id, $producer_id, $post_status, $tags){
        array_filter($tags);
        if(!$tags){
            return false;
        }

        $time = time();
        $insert_tag = [];

        foreach($tags as $tag){
            $insert_tag[] = [
                'tag_post_id' => $tag,
                'post_id'     => $post_id,
                'producer_id' => $producer_id,
                'post_status' => $post_status,
                'create_time' => $time,
            ];
        }
        TagPostRela::insert($insert_tag);

        if($post_status == 1){
            foreach($tags as $tag){
                if($tag){
                    TagPost::updateRelaPostCount($producer_id, $tag, 1);
                }
            }
        }
    }

    public static function removeTagPostRela($post_id, $producer_id, $post_status, $tags){
        array_filter($tags);
        if(!$tags){
            return false;
        }

        TagPostRela::where('producer_id', $producer_id)
            ->where('post_id', $post_id)
            ->where('post_status', $post_status)
            ->whereIn('tag_post_id', $tags)
            ->delete();

        if($post_status == 1){
            foreach($tags as $tag){
                if($tag){
                    TagPost::updateRelaPostCount($producer_id, $tag, -1);
                }
            }
        }
    }

    public static function getTagPostRela($post_id, $producer_id, $post_status){
        $model = TagPostRela::where('producer_id', $producer_id)
            ->where('post_id', $post_id)
            ->where('post_status', $post_status)
            ->get()
            ->toArray();
        $arr = [];
        foreach($model as $v){
            $arr[] = $v['tag_post_id'];
        }
        return $arr;
    }


}
