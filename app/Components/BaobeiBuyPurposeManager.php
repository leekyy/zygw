<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\BaobeiBuyPurpose;
use Qiniu\Auth;

class BaobeiBuyPurposeManager
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
        $buy_purpose = BaobeiBuyPurpose::where('id', '=', $id)->first();
        return $buy_purpose;
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
        $buy_purposes = BaobeiBuyPurpose::where('status', '=', '1')->orderby('id', 'asc')->get();
        return $buy_purposes;
    }
}