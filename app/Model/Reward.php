<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reward
 * @package App\Model
 *
 * @property int $producer_id
 * @property int $reward_fund
 * @property string $reward_title
 * @property string $reward_description
 * @property string $create_time
 * @property string $update_time
 *
 */

class Reward extends Model
{
    //
    protected $table = 'reward';

    public $timestamps = false;


    public static function addReward($producer_id, $reward_fund, $reward_title, $reward_description){
        $model = new Reward();
        $time = time();
        $model->producer_id = $producer_id;
        $model->reward_fund = $reward_fund;
        $model->reward_title = $reward_title;
        $model->reward_description = $reward_description;
        $model->create_time = $time;
        $model->update_time = $time;
        $model->save();

        Producer::updateProducerRewardCount($producer_id, 1);
        return $model;
    }

    public static function getReward($producer_id){
        $model = Reward::where('producer_id', $producer_id)
            ->orderBy('reward_fund')
            ->get();
        return $model;
    }

}
