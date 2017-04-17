<?php

namespace App\Http\Controllers;

use App\Libraries\Response;
use App\Model\Producer;
use App\Model\UserCheck;
use Illuminate\Http\Request;
use App\Model\UserFeed;

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
        $feed = UserFeed::getUserFeed($user['id'], 1);
        $feed = Producer::getProducerInfoByFeed($feed);
        $data = [
            'user' => $user,
            'feed' => $feed,
        ];
        return view('home',$data);
//        return $data;
    }

    public function getTimeline(){
        $user = UserCheck::getUserArray();
        $cur_page = \Request::input('cur_page', 1);
        $per_page = 5;
        $feed = UserFeed::getUserFeed($user['id'], $cur_page, $per_page);
        $feed = Producer::getProducerInfoByFeed($feed);
        $data = [
            'feed' => $feed,
            'has_more' => count($feed) == $per_page? 1: 0,
        ];

        return Response::formatJson(200, '成功', $data);
    }
}
