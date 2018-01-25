<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\KH;
use Qiniu\Auth;

class KeHuManager
{
    /*
     * 获取首页生效的客户信息
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getADsForIndex()
    {
        $ads = KH::where('status', '=', '0')->orderby('seq', 'desc')->get();
        return $ads;
    }

    /*
     * 根据id获取客户
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     */
    public static function getADById($id)
    {
        $ad = KH::where('id', '=', $id)->first();
        return $ad;
    }

    /*
     * 获取全部未删除的客户
     *
     * By TerryQI
     *
     * 2017-12-04
     *
     */
    public static function getAllADs()
    {
        $ads = KH::orderBy('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $ads;
    }


    /*
     * 设置客户信息，用于编辑、
     *
     * By TerryQi
     *
     */
    public static function setAD($ad, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $ad->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('kehu_name', $data)) {
            $ad->kehu_name = array_get($data, 'kehu_name');
        }
        if (array_key_exists('telephone', $data)) {
            $ad->telephone = array_get($data, 'telephone');
        }
        if (array_key_exists('status', $data)) {
            $ad->status = array_get($data, 'status');
        }
        return $ad;
    }
}