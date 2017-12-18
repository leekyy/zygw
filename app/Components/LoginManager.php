<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\User;

class LoginManager
{

    /*
     * 表单提交
     *
     * By Yinyue
     *
     * 2017-12-11
     */
    public static function enter($data)
    {
        $user = User::where('xcx_openid', '=', $data['xcx_openid'])->first();

        $user->nick_name = $data['nick_name'];
        $user->cardID = $data['cardID'];
        $user->email = $data['email'];
        $user->phonenum = $data['phonenum'];
        $user->save();

        return $user;
    }




}