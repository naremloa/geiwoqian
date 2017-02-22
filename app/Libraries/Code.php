<?php

namespace App\Libraries;

class Code{
    public static function generateCode($length = 5){
        $code = null;
        $code_all = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $max_length = strlen($code_all) - 1;
        for($i = 0; $i < $length; $i++){
            $code .= $code_all[rand(0, $max_length)];
        }
        return $code;
    }
}