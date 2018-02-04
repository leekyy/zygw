<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\BaobeiPayWay;
use Qiniu\Auth;

class BaobeiPayWayManager
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
        $pay_way = BaobeiPayWay::where('id', '=', $id)->first();
        return $pay_way;
    }

    /*
     * 获取生效的支付方式
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     */
    public static function getListValid()
    {
        $pay_ways = BaobeiPayWay::where('status', '=', '1')->orderby('id', 'asc')->get();
        return $pay_ways;
    }
}