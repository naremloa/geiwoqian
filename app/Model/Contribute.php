<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Follow;
use App\Model\UserCheck;
use App\Model\Producer;

/**
 * Class Contribute
 * @package App\Model
 *
 * @property int $user_id
 * @property int $fund_per_month
 * @property int $contribute_grade
 * @property int $producer_id
 * @property string $create_time
 *
 */

class Contribute extends Model
{
    //
    protected $table = 'contribute';

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
    public static function addContributer($user_id = null, $user_follow_count = null,$fun_per_month, $contribute_grade, $producer_id){
        //没有传用户相关信息时，默认操作者为当前登陆用户
        if(!$user_id){
            $user = UserCheck::getUserArray();
            $user_id = $user['id'];
            $user_follow_count = $user['follow_count'];
        }
        $time = time();
        $model = new Contribute();
        $model->user_id = $user_id;
        $model->fund_per_month = $fun_per_month;
        $model->contribute_grade = $contribute_grade;
        $model->producer_id = $producer_id;
        $model->create_time = $time;
        $model->save();

        $model->toArray();
//        todo
        if(!$user_follow_count){
            $user_follow_count = User::getUserFollowCount($user_id);
        }
        $producer_follower_count = Producer::getProducer($producer_id)['follower_count'];
//        成为参与者后自动关注
        Follow::addFollow($model['user_id'],$user_follow_count,$model['contribute_grade'],$model['producer_id'],$producer_follower_count);

        return $model;
    }

    public static function getbacker($producer_id){
        $contributers = Contribute::where('producer_id',$producer_id)->get()->toArray();

    }
}
