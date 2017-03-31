<?php

namespace App\Http\Controllers\Identify;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserOperate;
use App\Libraries\Response;
use App\Model\UserCheck;

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
            return Response::formatJson(400,'账号不能为空');
        }
        if(!$password){
            //请填写密码
            return Response::formatJson(400,'密码不能为空');
        }


//        验证信息是否符合标准

//        查找账号是否存在
        $model = User::findByAccount($account);
        if($model){
//            验证账号密码是否匹配
            if(User::checkPassword($model, $password)){
//                验证账号状态是否异常

//                存取登入信息
                $auth_token = UserOperate::login($model->toArray());
                $redirect = '/';
                return Response::formatJson(200,'登入成功',['auto_token' => $auth_token, 'redirect' => $redirect]);
            }
        }
        return Response::formatJson(404, '邮箱或密码错误');
    }

    public function logout(){
        UserOperate::logout();

        return redirect('/');
    }
}