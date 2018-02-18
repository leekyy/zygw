<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\JifenChangeRecord;
use Qiniu\Auth;

class JifenChangeRecordManager
{
    /*
     * 根据用户id获取积分变更记录
     *
     * By TerryQi
     *
     * 2018-02-18
     *
     */
    public static function getListByUserId($user_id)
    {
        $jifen_change_records = JifenChangeRecord::where('user_id', '=', $user_id)->orderby('id', 'desc')->get();
        return $jifen_change_records;
    }


    /*
     * 设置积分变更记录
     *
     * By TerryQi
     *
     */
    public static function setJifenChangeRecord($jifen_change_record, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $jifen_change_record->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('jifen', $data)) {
            $jifen_change_record->jifen = array_get($data, 'jifen');
        }
        if (array_key_exists('record', $data)) {
            $jifen_change_record->record = array_get($data, 'record');
        }
        return $jifen_change_record;
    }
}