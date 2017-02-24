<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    protected $table = 'user';
    protected $hidden = ['password'];

    public $timestamps = false;

    public static function findByAccount($account) {
        return User::where("name", '=', $account)->first();
    }

    public static function checkPassword(User $model, $password){
        if($model->password == UserOperate::encryptPassword($password, $model->create_time)){
            return true;
        }else{
            return false;
        }
    }


}
