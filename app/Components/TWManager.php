<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\User;

class TWManager
{
    /*
         * 设置图文步骤信息，用于编辑
         *
         * By TerryQi
         *
         */
    public static function setTW($tw, $data)
    {
        if (array_key_exists('f_id', $data)) {
            $tw->f_id = array_get($data, 'f_id');
        }
        if (array_key_exists('f_type', $data)) {
            $tw->f_type = array_get($data, 'f_type');
        }
        if (array_key_exists('text', $data)) {
            $tw->text = array_get($data, 'text');
        }
        if (array_key_exists('img', $data)) {
            $tw->img = array_get($data, 'img');
        }

        return $tw;
    }
}