<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    protected $table = 'user';
    protected $hidden = ['password'];

    public $timestamps = false;

    public static function findByEmail($account) {
        return User::where("name", '=', $account)->first();
    }


}
