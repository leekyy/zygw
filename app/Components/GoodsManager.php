<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Goods;
use Qiniu\Auth;

class GoodsManager
{
    /*
     * 获取全部的商品信息
     *
     * By TerryQi
     *
     * 2017-01-21
     *
     */
    public static function getListByStatusPaginate($status)
    {
        $goods = Goods::wherein('status', $status)->orderby('seq', 'desc')->paginate(Utils::PAGE_SIZE);
        return $goods;
    }

    /*
     * 根据id获取申请信息
     *
     * By TerryQi
     *
     * 2017-01-21
     *
     */
    public static function getById($id)
    {
        $goods = Goods::where('id', '=', $id)->first();
        return $goods;
    }

    /*
     * 设置申请成为案场负责人，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setGoods($goods, $data)
    {
        if (array_key_exists('name', $data)) {
            $goods->name = array_get($data, 'name');
        }
        if (array_key_exists('desc', $data)) {
            $goods->desc = array_get($data, 'desc');
        }
        if (array_key_exists('seq', $data)) {
            $goods->seq = array_get($data, 'seq');
        }
        if (array_key_exists('img', $data)) {
            $goods->image = array_get($data, 'img');
        }
        if (array_key_exists('jifen', $data)) {
            $goods->jifen = array_get($data, 'jifen');
        }
        if (array_key_exists('status', $data)) {
            $goods->status = array_get($data, 'status');
        }
        return $goods;
    }
}