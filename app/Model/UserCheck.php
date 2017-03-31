<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\Process\PhpProcess;
use App\Libraries\RequestCache;
use App\Model\User;

class UserCheck extends Model
{
    //
    const LOGIN_COOKIE_KEY = 'token';
    const CACHE_KEY = 'cache';
//    private static $userArray;

    /**
     * @return null
     */
    public static function getRequestToken(){
        $token = null;

        $token = \Request::cookie(self::LOGIN_COOKIE_KEY);
        return $token;
    }

    /**
     * @return null
     */
    public static function check(){
        return self::getRequestToken();
    }

    /**
     * 获取当前登录用户的各种相关信息
     *
     * @return array|void
     */
    public static function getUserArray(){
        $token = UserCheck::getRequestToken();
        if(is_null($token) || strlen($token) != 32){
            return [];
        }
        $user_id = UserCheck::getUseridByToken($token);
        if(!$user_id){
            return [];
        }
        $user = UserCheck::getCachedUserArray($user_id);
        if(!$user){
            return [];
        }else{
            $user = User::getUser($user_id);
        }
//        暂时不启用，视情况加上
//        UserCheck::$userArray = $user;

//        延长续费时间操作
        return $user;
    }

    private static function getUseridByToken($token){
        $token_key = self::LOGIN_COOKIE_KEY . '_token_user_id_' . $token;
        return \Redis::connection()->get($token_key);
    }

    /**
     * 由于使用id调度频繁，第一次调用后存cache以便后面使用
     *
     * @param $user_id
     * @return array|null
     */
    public static function getCachedUserArray($user_id){
        $cache_key = self::CACHE_KEY . '_user_info_' . $user_id;
        $cache = RequestCache::getValue($cache_key);
        if($cache !== null){
            $user = $cache;
        }else{
            $user = User::getUser($user_id);
            RequestCache::setValue($cache_key, $user);
        }
        return $user;
    }

    /**
     * 获取当前登录用户id
     *
     * @return string
     */
    public static function getUserId(){
        $model = UserCheck::getUserArray();
        if($model && isset($model['id'])){
            return $model['id'];
        }else{
            return '';
        }
    }

    /**
     * 获取当前登录用户url_slug
     *
     * @return string
     */
    public static function getUserSlug(){
        $model = UserCheck::getUserArray();
        if($model && isset($model['url_slug'])){
            return $model['url_slug'];
        }else{
            return '';
        }
    }
}
