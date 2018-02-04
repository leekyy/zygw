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
use App\Models\ZYGW;
use Qiniu\Auth;

class ZYGWManager
{


    /* 根据楼盘id获取顾问信息
     *
     * By Yinyue
     *
     * 2018-1-22
     */

    public static function getListByHouseIdPaginate($house_id)
    {
        $zygws = ZYGW::where('house_id', $house_id)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        //设置用户信息和楼盘信息
        return $zygws;
    }

    /*
     * 根据id获取顾问详细信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $zygw = ZYGW::where('id', '=', $id)->first();
        return $zygw;
    }

    /*根据楼盘id获取该楼盘下的所有置业顾问
     *
     * By Yinyue
     * 2018-1-24
     */

    public static function getListByHouseId($house_id)
    {
        $zygws = ZYGW::where('house_id', '=', $house_id)->get();
        return $zygws;
    }

    /*根据楼盘id获取该楼盘下的生效置业顾问，考虑status==1情况
     *
     * By Yinyue
     * 2018-1-24
     */

    public static function getListByHouseIdValid($house_id)
    {
        $zygws = ZYGW::where('house_id', '=', $house_id)->where('status', '=', '1')->get();
        return $zygws;
    }

    public static function setZYGW($zygw, $data)
    {
        if (array_key_exists('house_id', $data)) {
            $zygw->house_id = array_get($data, 'house_id');
        }
        if (array_key_exists('admin_id', $data)) {
            $zygw->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('name', $data)) {
            $zygw->name = array_get($data, 'name');
        }
        if (array_key_exists('phonenum', $data)) {
            $zygw->phonenum = array_get($data, 'phonenum');
        }
        return $zygw;
    }

}