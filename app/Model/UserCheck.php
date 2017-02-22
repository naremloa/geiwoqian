<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserCheck extends Model
{
    //
    const LOGIN_COOKIE_KEY = 'token';

    public static function getRequestToken(){
        $token = null;

        $token = \Request::cookie('token');
        return $token;
    }

    public static function check(){
        return self::getRequestToken();
    }
}
