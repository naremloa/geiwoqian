<?php

namespace App\Http\Controllers;

use App\Model\UserCheck;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    public function index(){
        $user = UserCheck::getUserArray();
        $data = [
            'user' => $user,
        ];
        return view('home',$data);
//        return $data;
//        return view('home');
    }
}
