<?php

namespace App\Model;

use App\Libraries\Code;
use Illuminate\Database\Eloquent\Model;
use App\Model\UserCheck;

class UserOperate extends Model
{
    //
    const LOGIN_COOKIE_KEY = 'token';
    const VALID_PERIOD = 259200; //86400 * 3

    /**
     * 登陆操作
     * @param $user
     * @return string
     */
    public static function login($user){
        $user_id = $user['id'];
        $token = self::generateCode($user_id, 'cookie');
        $expires = self::VALID_PERIOD;
//        存Redis
        self::setLoginToken($user_id, $token, $expires);
//        存Cookie
        self::setRequestToken($token, $expires);
        return $token;
    }

    /**
     * 登出操作
     */
    public static function logout(){
        $token = UserCheck::getRequestToken();
        if($token){
//            删除Redis存储信息
            self::removeRequestToken($token);
//            删除cookie
            self::setRequestToken($token,-1);
        }
    }

    public static function generateCode($user_id, $operate){
        $code = md5(uniqid($operate, true) . $user_id . Code::generateCode(5));
        return $code;
    }

    private static function removeRequestToken($token){
        $token_key = self::LOGIN_COOKIE_KEY . '_token_user_id_' . $token;
        \Redis::connection()->del($token_key);
    }

    private static function setLoginToken($user_id, $token, $expires){
        $token_key = self::LOGIN_COOKIE_KEY . '_token_user_id_' . $token;
        \Redis::connection()->set($token_key, $user_id);
        \Redis::connection()->expire($token_key, $expires);
    }

    private static function setRequestToken($token, $expires){
//            $domain = $_SERVER['HTTP_HOST'];
//            if (strpos($domain, ":") !== false) {
//                $domain = explode(":", $domain);
//                $domain = $domain[0];
//            }
//            setcookie('token', $token, time() + $expires, '/', '.'.$domain, true);
        //存cookie
        setcookie(self::LOGIN_COOKIE_KEY, $token, time() + $expires, '/');
    }

    public static function encryptPassword($password, $time) {
        $fake_salt = mb_substr($password, 0, 2, 'utf8') . substr($time, 3) . strlen($time);

        return md5($password . $time . $fake_salt);
    }
}
