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
use App\Models\HouseType;
use App\Models\Huxing;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class HouseTypeManager
{


    /*获取全部的楼盘标签信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getList()
    {
        $houseTypes = HouseType::orderby('id', 'asc')->get();
        return $houseTypes;
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
        $houseType = HouseType::where('id', '=', $id)->first();
        return $houseType;
    }


    /*
     * 根据id数组获取类型信息列表
     *
     * By TerryQi
     *
     * 2018-01-24
     */
    public static function getListByIds($ids)
    {
        $houseTypes = HouseType::wherein('id', $ids)->get();
        return $houseTypes;
    }

    /*
     * 设置楼盘标签
     *
     * By TerryQi
     *
     * 2018-01-27
     */
    public static function setHouseType($houseType, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $houseType->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('name', $data)) {
            $houseType->name = array_get($data, 'name');
        }
        return $houseType;
    }

}