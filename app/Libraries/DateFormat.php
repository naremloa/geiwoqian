<?php

namespace App\Libraries;

class DateFormat
{
    public static function addTimeShow($data){
        if(isset($data['create_time'])){
            $data['create_time_show'] = date("Y-m-d", $data['create_time']). ' '. date("G:i", $data['create_time']);
        }
        if(isset($data['publish_time'])){
            $data['publish_time_show'] = date("Y-m-d", $data['publish_time']). ' '. date("G:i", $data['publish_time']);
        }
        if(isset($data['update_time'])){
            $data['update_time_show'] = date("Y-m-d", $data['update_time']). ' '. date("G:i", $data['update_time']);
        }
        return $data;
    }
}