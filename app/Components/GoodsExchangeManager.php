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
use App\Models\GoodsExchange;
use Qiniu\Auth;

class GoodsExchangeManager
{
    /*
     * 获取全部的商品兑换信息
     *
     * By TerryQi
     *
     * 2017-01-21
     *
     */
    public static function getListPaginate($status)
    {
        $goodsExchanges = GoodsExchange::orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $goodsExchanges;
    }

    /*
     * 根据id获取商品兑换信息
     *
     * By TerryQi
     *
     * 2017-01-21
     *
     */
    public static function getById($id)
    {
        $goodsExchange = GoodsExchange::where('id', '=', $id)->first();
        return $goodsExchange;
    }

    /*
     * 根据级别获取积分兑换订单详情
     *
     * By TerryQi
     *
     */
    public static function getInfoByLevel($goodsExchange, $level)
    {
        $goodsExchange->user = UserManager::getUserInfoById($goodsExchange->user_id);
        $goodsExchange->goods = GoodsManager::getById($goodsExchange->goods_id);
        return $goodsExchange;
    }


    /*
     * 根据用户id获取兑换列表
     *
     * By TerryQi
     *
     * 2018-01-22
     */
    public static function getListByUserId($user_id)
    {
        $goodsExchanges = GoodsExchange::where('user_id', '=', $user_id)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $goodsExchanges;
    }


    /*
     * 设置申请成为案场负责人，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setGoodsExchange($goodsExchange, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $goodsExchange->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('goods_id', $data)) {
            $goodsExchange->goods_id = array_get($data, 'goods_id');
        }
        if (array_key_exists('dh_time', $data)) {
            $goodsExchange->dh_time = array_get($data, 'dh_time');
        }
        if (array_key_exists('total_jifen', $data)) {
            $goodsExchange->total_jifen = array_get($data, 'total_jifen');
        }
        if (array_key_exists('status', $data)) {
            $goodsExchange->status = array_get($data, 'status');
        }
        return $goodsExchange;
    }
}