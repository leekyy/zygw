<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Libs\CommonUtils;
use App\Models\TWStep;
use App\Models\ViewModels\GoodDetailView;
use App\Models\Good;

class GoodManager
{
    /*
         * 设置服务信息，用于编辑
         *
         * By TerryQi
         *
         */
    public static function setGood($good, $data)
    {
        if (array_key_exists('title', $data)) {
            $good->title = array_get($data, 'title');
        }
        if (array_key_exists('desc', $data)) {
            $good->desc = array_get($data, 'desc');
        }
        if (array_key_exists('show_price', $data)) {
            $good->show_price = array_get($data, 'show_price');
        }
        if (array_key_exists('price', $data)) {
            $good->price = array_get($data, 'price');
        }
        if (array_key_exists('count', $data)) {
            $good->count = array_get($data, 'count');
        }
        if (array_key_exists('img', $data)) {
            $good->img = array_get($data, 'img');
        }
        if (array_key_exists('type', $data)) {
            $good->type = array_get($data, 'type');
        }
        if (array_key_exists('flag', $data)) {
            $good->flag = array_get($data, 'flag');
        }
        if (array_key_exists('seq', $data)) {
            $good->seq = array_get($data, 'seq');
        }
        if (array_key_exists('status', $data)) {
            $good->status = array_get($data, 'status');
        }
        return $good;
    }


    //获取服务列表
    public static function getGoodList($data)
    {
        //获取有效服务:
        $good_infos = Good::where('status', '=', '1');
        //设置type
        if (array_key_exists('type', $data)) {
            $good_infos = $good_infos->where('type', '=', $data['type']);
        }
        $good_infos = $good_infos->orderby('seq', 'desc')->orderby('id', 'desc')->paginate(CommonUtils::PAGE_SIZE);
        return $good_infos;
    }

    //根据id获取服务详情
    public static function getGoodById($id)
    {
        $good_info = Good::where('id', '=', $id)->first();
        $tw_steps = TWStep::where('f_id', '=', $id)->where('f_type', '=', '1')->get();
        $result = new GoodDetailView();
        $result->good_info = $good_info;
        $result->tw_steps = $tw_steps;
        return $result;
    }
}