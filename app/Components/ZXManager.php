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
use App\Models\ViewModels\ZXDetailView;
use App\Models\ZX;

class ZXManager
{


    /*
         * 设置资讯信息，用于编辑
         *
         * By TerryQi
         *
         */
    public static function setZX($zx, $data)
    {
        if (array_key_exists('title', $data)) {
            $zx->title = array_get($data, 'title');
        }
        if (array_key_exists('intro', $data)) {
            $zx->intro = array_get($data, 'intro');
        }
        if (array_key_exists('author', $data)) {
            $zx->author = array_get($data, 'author');
        }
        if (array_key_exists('img', $data)) {
            $zx->img = array_get($data, 'img');
        }
        if (array_key_exists('type', $data)) {
            $zx->type = array_get($data, 'type');
        }
        if (array_key_exists('flag', $data)) {
            $zx->flag = array_get($data, 'flag');
        }
        if (array_key_exists('seq', $data)) {
            $zx->seq = array_get($data, 'seq');
        }
        if (array_key_exists('status', $data)) {
            $zx->status = array_get($data, 'status');
        }
        return $zx;
    }

    //获取资讯列表
    public static function getZXList($data)
    {
        //获取有效资讯:
        $zx_infos = ZX::where('status', '=', '1');
        //设置type
        if (array_key_exists('type', $data)) {
            $zx_infos = $zx_infos->where('type', '=', $data['type']);
        }
        $zx_infos = $zx_infos->orderby('seq', 'desc')->orderby('id', 'desc')->paginate(CommonUtils::PAGE_SIZE);
        return $zx_infos;
    }

    //根据id获取咨询详情
    public static function getZXById($data)
    {
        $zx_info = ZX::where('id', '=', $data['id'])->first();
        $tw_steps = TWStep::where('f_id', '=', $data['id'])->where('f_type', '=', '0')->get();
        $result = new ZXDetailView();
        $result->zx_info = $zx_info;
        $result->tw_steps = $tw_steps;
        return $result;
    }

    //增加资讯展示数
    public static function addShowNum($id)
    {
        $result = ZX::where('id', '=', $id)
            ->increment('show_num');
    }
}