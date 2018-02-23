<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/12/4
 * Time: 9:23
 */
namespace App\Components;
class Utils
{
    const PAGE_SIZE = 10;

    /*
     * 判断一个对象是不是空
     *
     * By TerryQi
     *
     * 2017-12-23
     *
     */
    public static function isObjNull($obj)
    {
        if ($obj == null || $obj == "") {
            return true;
        }
        return false;
    }

    /*
     * 将id字符串数组转换为int数组
     *
     * By TerryQi
     *
     * 2018-01-27
     */
    public static function strArrToIntArr($str_arr)
    {
        $int_arr = array();
        foreach ($str_arr as $str) {
            array_push($int_arr, intval($str));
        }
        return $int_arr;
    }

    /*
     * 判断一个字符串是不是电话号码
     *
     * By TerryQi
     *
     * 2018-1-21
     *
     */
    public static function isPhonenum($phonenum)
    {
        if (preg_match("/^1[34578]{1}\d{9}$/", $phonenum)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 判断一个宣教数据集的id是否在数组中的id，即用于新建宣教步骤时，判断是否需要删除该步骤
     *
     * By TerryQi
     *
     * 2017-12-23
     *
     */
    public static function isIdInArray($id, $arrs)
    {
        foreach ($arrs as $arr) {
            if (array_key_exists('id', $arr) && $arr['id'] == $id) {
                return true;
            }
        }
        return false;
    }

    /*
 * 生成订单号
 *
 * By TerryQi
 *
 * 2017-01-12
 *
 */
    public static function generateTradeNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);;
    }

    /*
     * 根据生日计算年龄
     *
     * By TerryQi
     *
     * 2017-12-26
     *
     */
    public static function getAge($birthday)
    {
        $age = 0;
        if (!empty($birthday)) {
            $age = strtotime($birthday);
            if ($age === false) {
                return 0;
            }
            list($y1, $m1, $d1) = explode("-", date("Y-m-d", $age));
            list($y2, $m2, $d2) = explode("-", date("Y-m-d"), time());
            $age = $y2 - $y1;
            if ((int)($m2 . $d2) < (int)($m1 . $d1)) {
                $age -= 1;
            }
        }
        return $age;
    }

    /*
     * 根据times和unit获取以天为单位的数值，用于后续的日期计算
     *
     * By TerryQI
     *
     * 2017-12-31
     *
     */
    public static function computeDaysByUnit($times, $unit)
    {
        $unit_value = 1;
        switch ($unit) {
            case "0":       //日
                $unit_value = 1;
                break;
            case "1":       //周
                $unit_value = 7;
                break;
            case "2":       //月
                $unit_value = 30;
                break;
        }
        return ($times * $unit_value);
    }


    /*
     * 模糊化手机号
     *
     * By TerryQi
     *
     * 2018-02-23
     */
    public static function hidePhonenum($phonenum)
    {
        $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i', $phonenum); //固定电话
        if ($IsWhat == 1) {
            return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i', '$1****$2', $phonenum);
        } else {
            return preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $phonenum);
        }
    }
}