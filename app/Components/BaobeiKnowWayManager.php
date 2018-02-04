<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\BaobeiKnowWay;
use Qiniu\Auth;

class BaobeiKnowWayManager
{
    /*
     * 根据id获取信息
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getById($id)
    {
        $know_way = BaobeiKnowWay::where('id', '=', $id)->first();
        return $know_way;
    }

    /*
     * 获取生效的购买目的
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     */
    public static function getListValid()
    {
        $know_ways = BaobeiKnowWay::where('status', '=', '1')->orderby('id', 'asc')->get();
        return $know_ways;
    }
}