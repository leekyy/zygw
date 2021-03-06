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
    /*根据楼盘id获取该楼盘下的所有产品
     *
     * By Yinyue
     * 2018-1-24
     */
    public static function getListByHouseId($house_id)
    {
        $houseClients = HouseClient::where('house_id', '=', $house_id)->get();
        return $houseClients;
    }
    /*
     * 根据客户phonenum，判断该客户是否是该房地产商的客户
     *
     * By TerryQi
     *
     * 2018-02-03
     *
     */
    public static function isClientAsDeveloperClient($phonenum, $house_id)
    {
        $houseClients = self::getListByHouseId($house_id);
        foreach ($houseClients as $houseClient) {
            if (self::isPhonenumMatch($phonenum, $houseClient->phonenum)) {
                return true;
            }
        }
        return false;
    }
    /*
     * 进行正则匹配，匹配规则为除中间4位其余位置可以匹配
     *
     * By TerryQi
     *
     * 2018-02-03
     *
     */
    public static function isPhonenumMatch($phonenum, $match_phonenum)
    {
        //前3位是否匹配
        if (substr($phonenum, 0, 3) != substr($match_phonenum, 0, 3)) {
            return false;
        }
        //后4位是否匹配
        if (substr($phonenum, -4) != substr($match_phonenum, -4)) {
            return false;
        }
        return true;
    }

    /*
     * 设置房产商客户列表
     *
     * By TerryQi
     *
     * 2018-02-08
     */

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