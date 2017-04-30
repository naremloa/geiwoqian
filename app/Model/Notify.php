<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Notify
 * @package App\Model
 *
 * @property int $id
 * @property  int $user_id
 * @property  int $producer_id
 * @property  int $to_user_id                通知接收人
 * @property  string $action
 * @property  int $status                    默认为0， 未读为1， 已读为2， 删除为4
 * @property  string $create_time
 * @property  string $update_time
 *
 */

class Notify extends Model
{
    //
    protected $table = 'notify';

    public $timestamps = false;

    public static function addNotify($user_id, $producer_id, $to_user_id, $action){
        $model = new Notify();
        $time = time();
        $model->user_id = $user_id;
        $model->producer_id = $producer_id;
        $model->to_user_id = $to_user_id;
        $model->action = $action;
        $model->status = 1;
        $model->create_time = $time;
        $model->update_time = $time;
        $model->save();
    }

    public static function getNotify($user_id, $limit_num){
        $model = Notify::where('to_user_id', $user_id)
            ->orderby('update_time','desc')
            ->take($limit_num)
            ->get()
            ->toArray();
        return $model;
    }

    public function updateNotifyRead($user_id, $limit_num){
        $model = Notify::where('user_id', $user_id)
            ->orderby('update_time')
            ->take($limit_num)
            ->update(['status' => 2]);
    }
}
