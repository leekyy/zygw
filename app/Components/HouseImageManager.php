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
use App\Models\HouseImage;
use App\Models\Huxing;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class HouseTypeManager
{


    /*获取全部的楼盘图片信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getList()
    {
        $houseImage = HouseImage::orderby('id', 'asc')->get();
        return $houseImage;
    }

    /*
     * 根据id获取楼盘图片信息
     *
     * By Yinyue
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $houseImage = HouseImage::where('id', '=', $id)->first();
        return $houseImage;
    }


    /*
     * 根据id数组获取图片信息列表
     *
     * By Yinyue
     *
     * 2018-03-08
     */
    public static function getListByIds($ids)
    {
        $houseImage = HouseImage::wherein('id', $ids)->get();
        return $houseImage;
    }

    /*
     * 设置楼盘图片
     *
     * By Yinyue
     *
     * 2018-03-08
     */
    public static function setHouseImage($houseImage, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $houseImage->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('image', $data)) {
            $houseImage->name = array_get($data, 'image');
        }
        return $houseImage;
    }

}