<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\ResetDealInfoRecord;
use Qiniu\Auth;

class ResetDealInfoRecordManager
{

    /*
     * 根据报备id获取修改记录列表
     *
     * By TerryQi
     *
     * 2018-02-19
     *
     */
    public static function getListByBaobeiId($baobei_id)
    {
        $list = ResetDealInfoRecord::where('baobei_id', '=', $baobei_id)->orderby('id', 'desc')->get();
        return $list;
    }


    /*
     * 设置值
     *
     * By TerryQi
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('baobei_id', $data)) {
            $info->baobei_id = array_get($data, 'baobei_id');
        }
        if (array_key_exists('desc', $data)) {
            $info->desc = array_get($data, 'desc');
        }
        return $info;
    }
}