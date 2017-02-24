<?php

namespace App\Http\Controllers;

use App\Model\UserOperate;
use Illuminate\Http\Request;
use App\Model\User;

class RegisterController extends Controller
{
    //
    public function postregister(){
        $account = trim(\Request::input('account'));
        $password = trim(\Request::input('password'));
        $time = time();

        $model = User::findByEmail($account);
        if($model){

        }else{
            $model = new User();
        }
        $model->name = $account;
        $model->url_slug = $account;
        $model->password = UserOperate::encryptPassword($password, $time);
        print_r($model->password);
        $model->status = 1;
        $model->create_time = $time;
        $model->update_time = $time;
        $model->save();
        return $model;

    }
}
