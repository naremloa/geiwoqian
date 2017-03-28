<?php

namespace App\Http\Controllers;

use App\Model\UserOperate;
use Illuminate\Http\Request;
use App\Model\User;
use App\Libraries\Response;

class RegisterController extends Controller
{
    //
    public function postregister(){
        $account = trim(\Request::input('account'));
        $password = trim(\Request::input('password'));
        $email = trim(\Request::input('email'));
        $time = time();

//        $model = User::findByEmail($account);
        $model = User::findByAccount($account);
        if($model){
            return Response::formatJson(404,'账号已存在');
        }else{
            $model = new User();
        }
        $model->name = $account;
        $model->url_slug = $account;
        $model->password = UserOperate::encryptPassword($password, $time);
        $model->email = $email;
        $model->status = 1;
        $model->intro = '';
        $model->avatar = 'default';
        $model->cover = 'default';
        $model->create_time = $time;
        $model->update_time = $time;
        $model->register_time = $time;
        $model->active_time = $time;
        $model->save();
        unset($model->id);
        return $model;

    }
}
