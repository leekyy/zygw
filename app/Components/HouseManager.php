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
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class HouseManager
{


    /*获取全部的楼盘信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getListByStatusPaginate($status)
    {
        $houses = House::wherein('status', $status)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        //设置用户信息和楼盘信息
        return $houses;
    }

    /*
     * 根据search_word进行搜索
     *
     * By TerryQi
     *
     * 2018-01-27
     */
    public static function searchByName($search_word)
    {
        $hourses = House::where('title', 'like', '%' . $search_word . '%')->paginate(Utils::PAGE_SIZE);
        return $hourses;
    }

    /*
     * 根据状态进行分页
     *
     * By yinyue
     *
     */
    public static function getListPaginate()
    {
        $houses = House::orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $houses;
    }

    /*
     * 根据id获取楼盘详细信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $house = House::where('id', '=', $id)->first();
        return $house;
    }


    /*
     * 根据级别获取房源详细信息
     *
     * By TerryQi
     *
     * 2018-01-27
     *
     * $level数组
     *
     * 0：带types和labels信息
     *
     */
    public static function getHouseInfoByLevel($house, $level)
    {
        if (strpos($level, '0') !== false) {
            //获得类型标签含义
            $types = explode(',', $house->type_ids);
            $house->types = HouseTypeManager::getListByIds($types);
            $labels = explode(',', $house->label_ids);
            $house->labels = HouselabelManager::getListByIds($labels);
        }
        return $house;
    }


    /*
     * 获取总楼盘数
     *
     * By TerryQi
     */
    public static function getAllLouPanNum()
    {
        $count = House::all()->count();
        return $count;
    }

    /*
    * 获取总房源数
    *
    * By TerryQi
    */
    public static function getAllFangYuanNum()
    {
        $count = DB::select('SELECT COUNT(distinct house_id) as rs FROM zygwdb.t_house_huxing;', []);
        return $count[0]->rs;
    }

    /*
       * 获取近N日的报表
       *
       * By TerryQi
       *
       */
    public static function getRecentDatas($day_num)
    {
        $data = DB::select('SELECT DATE_FORMAT( created_at, "%Y-%m-%d" ) as tjdate , COUNT(*)  as qdrs, SUM(title)  as psjfs FROM zygwdb.t_house_info GROUP BY tjdate order by tjdate desc limit 0,:day_num;', ['day_num' => $day_num]);
        return $data;
    }


    public static function setHouse($house, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $house->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('image', $data)) {
            $house->image = array_get($data, 'image');
        }
        if (array_key_exists('title', $data)) {
            $house->title = array_get($data, 'title');
        }
        if (array_key_exists('address', $data)) {
            $house->address = array_get($data, 'address');
        }
        if (array_key_exists('price', $data)) {
            $house->price = array_get($data, 'price');
        }
        if (array_key_exists('type_ids', $data)) {
            $house->type_ids = array_get($data, 'type_ids');
        }
        if (array_key_exists('size', $data)) {
            $house->size = array_get($data, 'size');
        }
        if (array_key_exists('label_ids', $data)) {
            $house->label_ids = array_get($data, 'label_ids');
        }
        if (array_key_exists('period', $data)) {
            $house->period = array_get($data, 'period');
        }
        if (array_key_exists('yongjin', $data)) {
            $house->yongjin = array_get($data, 'yongjin');
        }
        return $house;
    }

}