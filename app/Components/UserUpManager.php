<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\UserUp;
use Qiniu\Auth;

class UserUpManager
{
    /*
     * 获取全部的加盟申请
     *
     * By TerryQi
     *
     * 2017-01-21
     *
     */
    public static function getListByStatusPaginate($status)
    {
        $userUps = UserUp::wherein('status', $status)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        //设置用户信息和楼盘信息
        return $userUps;
    }

    /*
     * 根据用户id搜索已经是案场负责人的楼盘列表
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public static function getUserUpHousesByUserId($user_id)
    {
        $userUpHouses = UserUp::where("user_id", '=', $user_id)->where("status", '=', "1")->get();;
        return $userUpHouses;
    }

    /*
     * 判断一个用户是否已经是该楼盘的案场负责人
     *
     * By TeryQi
     *
     * 2018-01-21
     */
    public static function isUserAlreadyACFZ($user_id, $house_id)
    {
        $userUp = UserUp::where("user_id", '=', $user_id)->where("house_id", '=', $house_id)->where("status", '=', "1")->first();;
        return $userUp;
    }

    /*
     * 设置加盟申请详情
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public static function getUserUpInfoByLevel($userUp, $level)
    {
        $userUp->user = UserManager::getUserInfoById($userUp->user_id);
        $userUp->house = HouseManager::getById($userUp->house_id);
        $userUp->admin = AdminManager::getAdminInfoById($userUp->admin_id);
        return $userUp;
    }

    /*
     * 根据id获取申请信息
     *
     * By TerryQi
     *
     * 2017-01-21
     *
     */
    public static function getById($id)
    {
        $userUp = UserUp::where('id', '=', $id)->first();
        return $userUp;
    }


    /*
     * 设置申请成为案场负责人，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setUserUp($userUp, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $userUp->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('house_id', $data)) {
            $userUp->house_id = array_get($data, 'house_id');
        }
        if (array_key_exists('status', $data)) {
            $userUp->status = array_get($data, 'status');
        }
        if (array_key_exists('admin_id', $data)) {
            $userUp->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('sh_time', $data)) {
            $userUp->sh_time = array_get($data, 'sh_time');
        }
        if (array_key_exists('desc', $data)) {
            $userUp->desc = array_get($data, 'desc');
        }
        return $userUp;
    }
}