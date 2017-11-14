<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\Good;
use App\Models\ViewModels\HomeView;
use Qiniu\Auth;

class HomeManager
{

    /*
     * 获取首页
     *
     * By TerryQi
     *
     */
    public static function getHome($data)
    {
        $ad_infos = AD::where('status', '=', '1')->orderby('seq', 'desc')->get();
        $good_infos = Good::where('status', '=', '1')->where('type', '=', '0')->take(CommonUtils::PAGE_SIZE)->get();
        $result = new HomeView();
        $result->ad_infos = $ad_infos;
        $result->good_infos = $good_infos;
        return $result;
    }
}