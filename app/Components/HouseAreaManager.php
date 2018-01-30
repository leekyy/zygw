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


    /*获取全部的楼盘区域信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getList()
    {
        $houseAreas = HouseArea::orderby('id', 'asc')->get();
        return $houseAreas;
    }

    /*
     * 根据id获取楼盘区域信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $houseArea = HouseArea::where('id', '=', $id)->first();
        return $houseArea;
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
        $houseTypes = HouseLabel::wherein('id', $ids)->get();
        return $houseTypes;
    }

    /*
     * 设置楼盘区域
     *
     * By TerryQi
     *
     * 2018-01-27
     */
    public static function setHouseLabel($houseArea, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $houseArea->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('name', $data)) {
            $houseArea->name = array_get($data, 'name');
        }
        return $houseArea;
    }

}