<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Follow;
use App\Model\UserCheck;
use App\Model\Producer;
use App\Model\Notify;

/**
 * Class Contribute
 * @package App\Model
 *
 * @property int $user_id
 * @property int $fund_per_month
 * @property int $contribute_grade
 * @property int $producer_id
 * @property string $create_time
 * @property string $update_time
 *
 */

class Contribute extends Model
{
    //
    protected $table = 'contribute';


    public $primaryKey = null;
    public $timestamps = false;

    /**
     * 成为某个发起者的参与者
     * 数据插入成功后，会自动继续操作follow表，自动关注
     *
     * @param null $user_id
     * @param null $user_follow_count
     * @param $fun_per_month
     * @param $contribute_grade
     * @param $producer_id
     * @return Contribute
     */
    public static function addContributer($user_id = null, $user_follow_count = null,$fund_per_month, $contribute_grade, $producer_id){
        //没有传用户相关信息时，默认操作者为当前登陆用户
        if(!$user_id){
            $user = UserCheck::getUserArray();
            $user_id = $user['id'];
            $user_follow_count = $user['follow_count'];
        }
        $time = time();
        $model = new Contribute();
        $model->user_id = $user_id;
        $model->fund_per_month = $fund_per_month;
        $model->contribute_grade = $contribute_grade;
        $model->producer_id = $producer_id;
        $model->create_time = $time;
        $model->update_time = $time;
        $model->save();

        $model->toArray();
//        todo
        if(!$user_follow_count){
            $user_follow_count = User::getUserFollowCount($user_id);
        }
        $producer_follower_count = Producer::getProducer($producer_id)['follower_count'];

        $user_id_producer = Producer::getUseridByProducerid($producer_id);
        Notify::addNotify($user_id, $producer_id, $user_id_producer, 'contributer');
//        成为参与者后自动关注
        Follow::addFollow($model['user_id'], $user_follow_count,$model['contribute_grade'], $model['producer_id'], $producer_follower_count, $time, 1);

        return $model;
    }

    public static function updateContributerInfo($user_id, $fund_per_month, $contribute_grade, $producer_id){
        $model = Contribute::where('producer_id', $producer_id)
            ->where('user_id', $user_id)
            ->update(['fund_per_month' => $fund_per_month, 'contribute_grade' => $contribute_grade]);

        $user_id_producer = Producer::getUseridByProducerid($producer_id);
        Notify::addNotify($user_id, $producer_id, $user_id_producer, 'change_plan');

        Follow::updateFollowInfo($user_id, $contribute_grade, $producer_id);
        return $model;
    }

    public static function getContributer($producer_id){
        $contributers = Contribute::where('producer_id',$producer_id)->get()->toArray();
        return $contributers;
    }

    public static function getUserContributeGrade($user_id, $producer_id){
        $model = Contribute::where('producer_id', $producer_id)
            ->where('user_id', $user_id)
            ->first();
        return $model['contribute_grade'];
    }

    public static function isContribute($user_id, $producer_id){
        $model = Contribute::where('producer_id', $producer_id)
            ->where('user_id', $user_id)
            ->first();
        if(count($model)){
            return 1;
        }else{
            return 0;
        }
    }
}
