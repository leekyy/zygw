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

class HouseManager
{


/*获取全部的楼盘信息
 *
 * By Yinyue
 * 2018-1-22
 */

    public static function getListByStatusPaginate($status)
    {
        $house = House::wherein('status', $status)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        //设置用户信息和楼盘信息
        return $house;
    }
    public static function getListPaginate()
    {
        $house = House::orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $house;
    }
    /*
     * 根据id获取楼盘详细信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $house = House::where('id', '=', $id)->first();
        return $house;
    }
    /*根据楼盘id获取该楼盘下的所有房源
     *
     * By Yinyue
     * 2018-1-24
     */
    public static function getHouseById($house_id ){
        $huxing = Huxing::where('house_id','=',$house_id)->get();
        return $huxing;

    }




    public static function setHouse($house, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $house->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('image', $data)) {
            $house->image = array_get($data, 'image');
        }
        if (array_key_exists('title', $data)) {
            $house->title = array_get($data, 'title');
        }
        if (array_key_exists('address', $data)) {
            $house->address = array_get($data, 'address');
        }
        if (array_key_exists('price', $data)) {
            $house->price = array_get($data, 'price');
        }

        if (array_key_exists('type', $data)) {
            $house->type = array_get($data, 'type');
        }
        if (array_key_exists('size', $data)) {
            $house->size = array_get($data, 'size');
        }
        if (array_key_exists('label', $data)) {
            $house->label = array_get($data, 'label');
        }
        if (array_key_exists('period', $data)) {
            $house->period = array_get($data, 'period');
        }
        if (array_key_exists('yongjin', $data)) {
            $house->yongjin = array_get($data, 'yongjin');
        }
        return $house;
    }

}