<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Admin;
use Qiniu\Auth;

class AdminManager
{

    /*
     * 管理员登录
     *
     * By TerryQi
     *
     * 2018-01-20
     */
    public static function login($phonenum, $password)
    {
        $admin = Admin::where('phonenum', '=', $phonenum)->where('password', '=', $password)->first();
        return $admin;
    }

    /*
     * 根据id获取管理员信息
     *
     * By TerryQi
     *
     * 2018-01-20
     */
    public static function getAdminInfoById($id)
    {
        $admin = Admin::find($id);
        //如果获取管理员信息
        if ($admin) {
            $admin->token = null;       //隐藏管理员token
        }
        return $admin;
    }

    /*
     * 设置管理员信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setAdmin($admin, $data)
    {
        if (array_key_exists('name', $data)) {
            $admin->name = array_get($data, 'name');
        }
        if (array_key_exists('avatar', $data)) {
            $admin->avatar = array_get($data, 'avatar');
        }
        if (array_key_exists('phonenum', $data)) {
            $admin->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('password', $data)) {
            $admin->password = array_get($data, 'password');
        }
        if (array_key_exists('role', $data)) {
            $admin->role = array_get($data, 'role');
        }
        return $admin;
    }
}