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

    /*根据楼盘id获取该楼盘下的所有产品
     *
     * By Yinyue
     * 2018-1-24
     */

    public static function getListByHouseId($house_id)
    {
        $huxings = Huxing::where('house_id', '=', $house_id)->get();
        return $huxings;
    }

    /*
     * 获取户型详细信息
     *
     * By TerryQi
     */
    public static function getInfoByLevel($huxing, $level)
    {
        $huxing->admin = AdminManager::getAdminInfoById($huxing->admin_id);
        $huxing->type = HouseTypeManager::getById($huxing->type_id);
        return $huxing;
    }

    /*
     * 设置户型信息
     *
     * By TerryQi
     *
     * 2018-01-31
     */

    public static function setHuxing($huxing, $data)
    {
        if (array_key_exists('house_id', $data)) {
            $huxing->house_id = array_get($data, 'house_id');
        }
        if (array_key_exists('admin_id', $data)) {
            $huxing->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('image', $data)) {
            $huxing->image = array_get($data, 'image');
        }
        if (array_key_exists('type_id', $data)) {
            $huxing->type_id = array_get($data, 'type_id');
        }
        if (array_key_exists('size_min', $data)) {
            $huxing->size_min = array_get($data, 'size_min');
        }
        if (array_key_exists('size_max', $data)) {
            $huxing->size_max = array_get($data, 'size_max');
        }
        if (array_key_exists('huxing', $data)) {
            $huxing->huxing = array_get($data, 'huxing');
        }
        if (array_key_exists('benefit', $data)) {
            $huxing->benefit = array_get($data, 'benefit');
        }
        if (array_key_exists('orientation', $data)) {
            $huxing->orientation = array_get($data, 'orientation');
        }
        if (array_key_exists('reason', $data)) {
            $huxing->reason = array_get($data, 'reason');
        }
        if (array_key_exists('status', $data)) {
            $huxing->status = array_get($data, 'status');
        }
        if (array_key_exists('yongjin_type', $data)) {
            $huxing->yongjin_type = array_get($data, 'yongjin_type');
        }
        if (array_key_exists('yongjin_value', $data)) {
            $huxing->yongjin_value = array_get($data, 'yongjin_value');
        }
        return $huxing;
    }

}