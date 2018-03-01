<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\UserCheck;

class UserSettingController extends Controller
{
    //
    public function index(){
        $user = UserCheck::getUserArray();
        $data = [
            'user' => $user,
        ];
        return $data;
    }


}
