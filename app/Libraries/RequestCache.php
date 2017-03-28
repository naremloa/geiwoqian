<?php

namespace App\Libraries;

class RequestCache
{
    public static $data = [];

    /**
     * @param $key
     * @param $value
     */
    public static function setValue($key, $value){
        if(isset(self::$data[$key])){
            //有值情况下再次请求，报错
        }
        self::$data[$key] = $value;
    }

    public static function getValue($key){
        if(isset(self::$data[$key])){
            return self::$data[$key];
        }else{
            //没有相应值的情况下被请求，暂时返回null
            return null;
        }
    }
}