<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\User;

class Producer extends Model
{
    //
    protected $table = 'producer';

    public $timestamps = false;

    /**
     * 发起者信息对外输出时进行重写
     * 可输出信息包括：name, url_slug, avatar, cover
     * 发起者的输出信息格式化不输出id，后续操作统一用url_slug进行操作
     *
     * @param $old_info
     * @return array
     */
    public static function ProducerInfoOutput($old_info){
        $safe_info = array('url_slug','name','avatar','cover');
        $new_info = [];
        foreach($old_info as $k => $v){
            if(in_array($k, $safe_info)){
                $new_info[$k] = $v;
            }
        }
        return $new_info;
    }

    /**
     * 获取单发起者信息，已对输出数据格式化
     *
     * @param $user_id
     * @return array
     */
    public static function getProducer($user_id){
        $producer = Producer::where('user_id',$user_id)->first();
        if($producer){
            $producer = $producer->toArray();
            $producer = Producer::ProducerInfoOutput($producer);
        }
        return $producer;
    }


    public static function addProducer($user_id){
        $model = Producer::where('user_id',$user_id)->first();
        if($model){
            //若是producer表中已有相关数据，暂时返回null
            return null;
        }else{
            $model = new Producer();
            $user = User::getUser($user_id);
            $time = time();
            $model->user_id = $user['id'];
//            todo
//            这里暂时先这么写着，为了保持url_slug的唯一性，后面会增加成为发起者时填写url_slug和检测重复性功能
            $model->url_slug = $user['name'];
            $model->name = $user['name'];
            $model->avatar = $user['avatar'];
            $model->cover = 'default';
            $model->status = 1;
            $model->create_time = $time;
            $model->update_time = $time;
            $model->save();
            unset($model->id);
            unset($model->user_id);
            return $model;
        }
    }

    public static function deleteProducer($user_id){
        return;
    }
}
