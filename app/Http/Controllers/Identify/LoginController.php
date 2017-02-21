<?php

namespace App\Http\Controllers\Identify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller{
    public function postlogin(){
//        拿取登入信息
        $account = trim(\Request::input('account'));
        $password = trim(\Request::input('password'));

//        验证信息是否为空
        if(!$account){
            //请填写账号
        }
        if(!$password){
            //请填写密码
        }

//        验证信息是否符合标准

//        查找账号是否存在

//        验证账号密码是否匹配
        if(1){
//            验证账号状态是否异常

//            存取登入信息
            $token = 'aaaaaaaaaaaaaaaa';
            $expires = 86400000;
            $token_key = '_token_' . $token;
            \Redis::connection()->set($token_key, $account);
            \Redis::connection()->expire($token_key, $expires);

//            $domain = $_SERVER['HTTP_HOST'];
//            if (strpos($domain, ":") !== false) {
//                $domain = explode(":", $domain);
//                $domain = $domain[0];
//            }
            //存cookie
            setcookie('token', $token, time() + $expires, '/');
            return 200;
        }
    }
}