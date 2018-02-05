<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\House;
use App\Models\HouseArea;
use App\Models\Huxing;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class HouseAreaManager
{


    /*获取全部的楼盘标签信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getList()
    {
        $houseLabels = HouseArea::orderby('id', 'asc')->get();
        return $houseLabels;
    }

    /*
     * 根据id获取楼盘标签信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $houseLabel = HouseArea::where('id', '=', $id)->first();
        return $houseLabel;
    }




    /*
     * 根据id数组获取标签信息列表
     *
     * By TerryQi
     *
     * 2018-01-24
     */
    public static function getListByIds($ids)
    {
        $houseTypes = HouseArea::wherein('id', $ids)->get();
        return $houseTypes;
    }

    /*
     * 设置楼盘标签
     *
     * By TerryQi
     *
     * 2018-01-27
     */
    public static function setHouseLabel($houseLabel, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $houseLabel->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('name', $data)) {
            $houseLabel->name = array_get($data, 'name');
        }
        return $houseLabel;
    }

}