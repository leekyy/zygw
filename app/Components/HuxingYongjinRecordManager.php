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
use App\Models\Huxing;
use App\Models\HuxingYongjingRecord;
use App\Models\HuxingYongjinRecord;
use Qiniu\Auth;

class HuxingYongjinRecordManager
{


    /* 根据产品id获取设置记录信息
     *
     * By Yinyue
     *
     * 2018-1-22
     */

    public static function getListByHuxingIdPaginate($huxing_id)
    {
        $huxingYongjinRecords = HuxingYongjinRecord::where('huxing_id', $huxing_id)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $huxingYongjinRecords;
    }

    /*
     * 根据level获取设置详细信息
     *
     * By TerryQi
     *
     * 2018-02-01
     */
    public static function getInfoByLevel($huxingYongjinRecord, $level)
    {
        $huxingYongjinRecord->admin = AdminManager::getAdminInfoById($huxingYongjinRecord->admin_id);
        return $huxingYongjinRecord;
    }


    /*
     * 设置户型设置记录信息
     *
     * By TerryQi
     *
     * 2018-01-31
     */

    public static function setHuxingYongjinRecord($huxingYongjinRecord, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $huxingYongjinRecord->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('huxing_id', $data)) {
            $huxingYongjinRecord->huxing_id = array_get($data, 'huxing_id');
        }
        if (array_key_exists('record', $data)) {
            $huxingYongjinRecord->record = array_get($data, 'record');
        }
        return $huxingYongjinRecord;
    }

}