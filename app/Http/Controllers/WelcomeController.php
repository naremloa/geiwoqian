<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\UserCheck;

class WelcomeController extends Controller{
    public function index(){
//        检查是否存了cookie（是否登陆）
        if(UserCheck::check()){
            return redirect(url('/home'));
        }

        return view('welcome');
    }
}