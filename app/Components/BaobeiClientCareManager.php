<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\BaobeiClientCare;
use Qiniu\Auth;

class BaobeiClientCareManager
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
        $client_care = BaobeiClientCare::where('id', '=', $id)->first();
        return $client_care;
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
        $client_cares = BaobeiClientCare::where('status', '=', '1')->orderby('id', 'asc')->get();
        return $client_cares;
    }
}