<?php

namespace App\Libraries;

class RequestCache
{
    public static $data = [];
//    数据需要更新时
    public static $dirt_data = [];

    /**
     * @param $key
     * @param $value
     */
    public static function setValue($key, $value){
        if(isset(self::$data[$key])){
            if(isset(self::$dirt_data[$key]) && self::$dirt_data == 0){
                //有值，且没有脏数据的情况下再次请求，报错
            }
        }
        self::$data[$key] = $value;
        self::$dirt_data[$key] = 0;
    }

    public static function getValue($key){
        if(isset(self::$dirt_data) && self::$dirt_data == 0){
            if(isset(self::$data[$key])){
                return self::$data[$key];
            }else{
                //没有相应值的情况下被请求，暂时返回null
                return null;
            }
        }else{
            return null;
        }
    }

    public static function setDirtValue($key){
        self::$dirt_data[$key] = 1;
    }
}