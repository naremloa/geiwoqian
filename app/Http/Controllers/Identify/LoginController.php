<?php

namespace App\Http\Controllers\Identify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserOperate;

class LoginController extends Controller{
    public function postlogin(){
//        （初始化）注销账号
        UserOperate::logout();

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
            UserOperate::login();//缺参数，从数据库拿出的完整user信息

//            $token = 'aaaaaaaaaaaaaaaa';
//            $expires = 86400000;
//            $token_key = '_token_' . $token;
//            \Redis::connection()->set($token_key, $account);
//            \Redis::connection()->expire($token_key, $expires);

            //存cookie
//            setcookie('token', $token, time() + $expires, '/');
            return 200;
        }
    }
}