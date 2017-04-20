<?php

namespace App\Http\Controllers;

use App\Libraries\Response;
use Illuminate\Http\Request;
use App\Model\UserCheck;
use App\Model\Contribute;

class ContributeController extends Controller
{
    //
    public function postEditContribute(){
        $user = UserCheck::getUserArray();
        $producer_id = \Request::input('producer_id');
        $fund_per_month = \Request::input('fund_per_month');
        $contribute_grade = $fund_per_month;
        if(Contribute::isContribute($user['id'], $producer_id)){
            $model = Contribute::updateContributerInfo($user['id'], $fund_per_month, $contribute_grade, $producer_id);
        }else{
            $model = Contribute::addContributer($user['id'], $user['follow_count'], $fund_per_month, $contribute_grade, $producer_id);
        }
        return Response::formatJson(200, '成功', $model);
    }
}
