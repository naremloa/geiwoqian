<?php

namespace App\Http\Controllers\Identify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller{
    public function postlogin(){
//        ��ȡ������Ϣ
        $account = trim(\Request::input('account'));
        $password = trim(\Request::input('password'));

//        ��֤��Ϣ�Ƿ�Ϊ��
        if(!$account){
            //����д�˺�
        }
        if(!$password){
            //����д����
        }

//        ��֤��Ϣ�Ƿ���ϱ�׼

//        �����˺��Ƿ����

//        ��֤�˺������Ƿ�ƥ��
        if(1){
//            ��֤�˺�״̬�Ƿ��쳣

//            ��ȡ������Ϣ
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
            //��cookie
            setcookie('token', $token, time() + $expires, '/');
            return 200;
        }
    }
}