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
        $name = trim(\Request::input('name'));
        $password = trim(\Request::input('password'));
        $time = time();

//        $model = User::findByEmail($account);
        $model = User::findByAccount($account);
        if($model){
            return Response::formatJson(404,'账号已存在');
        }else{
            $model = new User();
        }
        $model->password = UserOperate::encryptPassword($password, $time);
        $model->name = $name;
        $model->email = $account;
        $model->status = 1;
        $model->role = 1;
        $model->avatar = 'default';
        $model->follow_count = 0;
        $model->create_time = $time;
        $model->update_time = $time;
        $model->register_time = $time;
        $model->active_time = $time;
        $model->save();


        unset($model->id);
        unset($model->status);
        return Response::formatJson(200,'成功',[]);
    }
}
