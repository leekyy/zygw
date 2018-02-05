<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\System;
use App\Models\SystemRecord;
use Qiniu\Auth;

class SystemManager
{
    /*
     * 获取系统配置信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getSystemInfo()
    {
        $systemInfo = System::orderby('id', 'desc')->first();
        return $systemInfo;
    }

    /*
     * 设置系统配置信息
     *
     * By TerryQi
     *
     */
    public static function setSystemInfo($systemInfo, $data)
    {
        if (array_key_exists('qd_jifen', $data)) {
            $systemInfo->qd_jifen = array_get($data, 'qd_jifen');
        }
        if (array_key_exists('tj_jifen', $data)) {
            $systemInfo->tj_jifen = array_get($data, 'tj_jifen');
        }
        return $systemInfo;
    }

    /*
     * 获取配置记录信息
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public static function getAllSystemRecords()
    {
        $systemRecords = SystemRecord::orderby('id', 'desc')->get();
        return $systemRecords;
    }

    /*
     * 根据级别获取systemRecord详情
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public static function getSystemRecordByLevel($sytemRecord, $level)
    {
        $sytemRecord->admin = AdminManager::getAdminInfoById($sytemRecord->admin_id);
        return $sytemRecord;
    }

    /*
     * 设置配置信息
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public static function setSystemRecords($systemRecord, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $systemRecord->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('desc', $data)) {
            $systemRecord->desc = array_get($data, 'desc');
        }
        return $systemRecord;
    }
}