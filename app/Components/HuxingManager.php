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
use App\Models\Huxing;
use Qiniu\Auth;

class HuxingManager
{


    /* 根据楼盘id获取户型信息
     *
     * By Yinyue
     *
     * 2018-1-22
     */

    public static function getListByHouseIdPaginate($house_id)
    {
        $huxings = Huxing::where('house_id', $house_id)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        //设置用户信息和楼盘信息
        return $huxings;
    }

    /*
     * 根据id获取户型详细信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $huxing = Huxing::where('id', '=', $id)->first();
        return $huxing;
    }

    /*根据楼盘id获取该楼盘下的所有房源
     *
     * By Yinyue
     * 2018-1-24
     */

    public static function getListByHouseId($house_id)
    {
        $huxings = Huxing::where('house_id', '=', $house_id)->get();
        return $huxings;
    }


    public static function setHuxing($house, $data)
    {
        if (array_key_exists('house_id', $data)) {
            $house->house_id = array_get($data, 'house_id');
        }

        if (array_key_exists('admin_id', $data)) {
            $house->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('image', $data)) {
            $house->image = array_get($data, 'image');
        }
        if (array_key_exists('type', $data)) {
            $house->type = array_get($data, 'type');
        }
        if (array_key_exists('size', $data)) {
            $house->size = array_get($data, 'size');
        }
        if (array_key_exists('price', $data)) {
            $house->price = array_get($data, 'price');
        }

        if (array_key_exists('benefit', $data)) {
            $house->benefit = array_get($data, 'benefit');
        }
        if (array_key_exists('orientation', $data)) {
            $house->orientation = array_get($data, 'orientation');
        }
        if (array_key_exists('reason', $data)) {
            $house->reason = array_get($data, 'reason');
        }

        return $house;
    }

}