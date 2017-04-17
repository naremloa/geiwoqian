<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\User;
use App\Libraries\DateFormat;

/**
 * Class Producer
 * @package App\Model
 *
 * @property int $id
 * @property int $user_id
 * @property string $url_slug 对发起者进行各种相关操作都用url_slug，不用id
 * @property string $name
 * @property string $intro
 * @property string $avatar
 * @property string $cover
 * @property int $status
 * @property int $balance    账户余额
 * @property int $get_fund_per_month    每月获赠金额
 * @property int $backer_count    支持人总数
 * @property int $follower_count    关注者总数
 * @property string $create_time
 * @property string $update_time
 *
 */

class Producer extends Model
{
    //
    protected $table = 'producer';
//    都使用url_slug操作，检查完后，去掉id
//    todo
//    protected $hidden = ['id'];

    public $timestamps = false;

    /**
     * 发起者信息对外输出时进行重写
     * 可输出信息包括：id, url_slug, name, intro, avatar, cover, get_fund_per_month, backer_count, follower_count
     * 发起者的输出信息格式化不输出id，后续操作统一用url_slug进行操作
     * 暂时先用着id
     *
     * @param $old_info
     * @return array
     */
    public static function ProducerInfoOutput($old_info){
        $safe_info = array('id', 'url_slug', 'name', 'intro', 'avatar', 'cover', 'get_fund_per_month', 'backer_count', 'follower_count');
        $new_info = [];
        foreach($old_info as $k => $v){
            if(in_array($k, $safe_info)){
                if($k == 'avatar'){
                    if($v == 'default'){
                        $v = '/img/default_avatar.png';
                    }
                }
                if($k == 'cover'){
                    if($v == 'default'){
                        $v = '/img/default_cover.png';
                    }
                }
                $new_info[$k] = $v;
            }
        }
        return $new_info;
    }

    /**
     * 获取单发起者信息，已对输出数据格式化
     *
     * @param $producer_id
     * @return array
     */
    public static function getProducer($producer_id){
        $producer = Producer::where('id',$producer_id)->first();
        if($producer){
            $producer = $producer->toArray();
            $producer = Producer::ProducerInfoOutput($producer);
        }
        return $producer;
    }

    public static function getProducerByUrlslug($url_slug){
        $producer = Producer::where('url_slug', $url_slug)->first();
        if($producer){
            $producer = $producer->toArray();
            $producer = Producer::ProducerInfoOutput($producer);
        }
        return $producer;
    }

    public static function getProducerByUserid($user_id){
        $producer = Producer::where('user_id', $user_id)->first();
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
            $model->intro = '';
            $model->avatar = $user['avatar'];
            $model->cover = 'default';
            $model->status = 1;
            $model->balance = 0;
            $model->get_fund_per_month = 0;
            $model->backer_count = 0;
            $model->follower_count = 0;
            $model->create_time = $time;
            $model->update_time = $time;
            $model->save();

            unset($model->id);
            unset($model->user_id);
            unset($model->status);
            return $model;
        }
    }

    public static function deleteProducer($user_id){
        return;
    }

    public static function updateProducerFollowerCount($producer_id, $operate_num){
        $model = Producer::where('id',$producer_id)->first();
        $model->follower_count = $model->follower_count + $operate_num;
        $model->save();
        return $model->toArray();
    }

    public static function getProducerInfoByFeed($feed){
        foreach($feed as $k){
            $k = DateFormat::addTimeShow($k);
            $k['producer_info'] = Producer::getProducer($k['producer_id']);

        }
        return $feed;
    }

    public static function getProducerAll(){
        return $model = Producer::all()->toArray();
    }
}
