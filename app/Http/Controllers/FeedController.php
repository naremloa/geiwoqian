<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Feed;

class FeedController extends Controller
{
    //
    public static function index($url_slug){
        $model = Feed::getFeedByUrlslug($url_slug);
        return $model;
    }
}
