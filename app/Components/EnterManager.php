<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Libs\CommonUtils;
use App\Models\Enter;
use App\Models\TWStep;
use App\Models\ViewModels\ZXDetailView;
use App\Models\ZX;

class EnterManager
{

    /*
     * 根据id获取企业信息
     *
     * By TerryQi
     *
     * 2017-10-13
     *
     */
    public static function getEnterById($id)
    {
        $enter = Enter::where('id', '=', $id)->first();
        return $enter;
    }


    /*
         * 设置企业信息，用于编辑
         *
         * By TerryQi
         *
         */
    public static function setEnter($enter, $data)
    {
        if (array_key_exists('name', $data)) {
            $enter->name = array_get($data, 'name');
        }
        if (array_key_exists('lice_img', $data)) {
            $enter->lice_img = array_get($data, 'lice_img');
        }
        if (array_key_exists('address', $data)) {
            $enter->address = array_get($data, 'address');
        }
        if (array_key_exists('code', $data)) {
            $enter->code = array_get($data, 'code');
        }
        if (array_key_exists('tax_code', $data)) {
            $enter->tax_code = array_get($data, 'tax_code');
        }
        if (array_key_exists('tax_img', $data)) {
            $enter->tax_img = array_get($data, 'tax_img');
        }
        if (array_key_exists('owner', $data)) {
            $enter->owner = array_get($data, 'owner');
        }
        if (array_key_exists('owner_card1', $data)) {
            $enter->owner_card1 = array_get($data, 'owner_card1');
        }
        if (array_key_exists('owner_card2', $data)) {
            $enter->owner_card2 = array_get($data, 'owner_card2');
        }
        if (array_key_exists('owner_no', $data)) {
            $enter->owner_no = array_get($data, 'owner_no');
        }
        if (array_key_exists('owner_tel', $data)) {
            $enter->owner_tel = array_get($data, 'owner_tel');
        }
        if (array_key_exists('user_id', $data)) {
            $enter->user_id = array_get($data, 'user_id');
        }
        return $enter;
    }

}