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
use App\Models\HouseClient;
use Qiniu\Auth;

class HouseClientManager
{


    /* 根据楼盘id获取房产商客户信息
     *
     * By Yinyue
     *
     * 2018-1-22
     */

    public static function getListByHouseIdPaginate($house_id)
    {
        $houseClients = HouseClient::where('house_id', $house_id)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        //设置用户信息和楼盘信息
        return $houseClients;
    }




    /*
     * 根据id获取房产商客户详细信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $houseClient = HouseClient::where('id', '=', $id)->first();
        return $houseClient;
    }

    /*根据楼盘id获取该楼盘下的所有房源
     *
     * By Yinyue
     * 2018-1-24
     */

    public static function getListByHouseId($house_id)
    {
        $houseClients = HouseClient::where('house_id', '=', $house_id)->get();
        return $houseClients;
    }


    public static function setHouseClient($houseClient, $data)
    {
        if (array_key_exists('house_id', $data)) {
            $houseClient->house_id = array_get($data, 'house_id');
        }
        if (array_key_exists('admin_id', $data)) {
            $houseClient->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('name', $data)) {
            $houseClient->name = array_get($data, 'name');
        }
        if (array_key_exists('phonenum', $data)) {
            $houseClient->phonenum = array_get($data, 'phonenum');
        }
        return $houseClient;
    }

}