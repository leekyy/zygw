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

class OrderManager
{
    /*
         * 设置服务信息，用于编辑
         *
         * By TerryQi
         *
         */
    public static function setOrder($order, $data)
    {
        if (array_key_exists('trade_no', $data)) {
            $order->trade_no = array_get($data, 'trade_no');
        }
        if (array_key_exists('user_id', $data)) {
            $order->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('prepay_id', $data)) {
            $order->prepay_id = array_get($data, 'prepay_id');
        }
        if (array_key_exists('good_id', $data)) {
            $order->good_id = array_get($data, 'good_id');
        }
        if (array_key_exists('count', $data)) {
            $order->count = array_get($data, 'count');
        }
        if (array_key_exists('channel', $data)) {
            $order->channel = array_get($data, 'channel');
        }
        if (array_key_exists('en_id', $data)) {
            $order->en_id = array_get($data, 'en_id');
        }
        if (array_key_exists('total_fee', $data)) {
            $order->total_fee = array_get($data, 'total_fee');
        }
        if (array_key_exists('description', $data)) {
            $order->description = array_get($data, 'description');
        }
        if (array_key_exists('pay_at', $data)) {
            $order->pay_at = array_get($data, 'pay_at');
        }
        if (array_key_exists('type', $data)) {
            $order->type = array_get($data, 'type');
        }
        if (array_key_exists('status', $data)) {
            $order->status = array_get($data, 'status');
        }
        if (array_key_exists('notify_url', $data)) {
            $order->notify_url = array_get($data, 'notify_url');
        }
        return $order;
    }

    //根据id获取服务详情
    public static function getOrderDetailInfo($order)
    {
        $order->user = UserManager::getUserInfoById($order->user_id);
        $order->good = GoodManager::getGoodById($order->good_id);
        $order->enter = EnterManager::getEnterById($order->en_id);
        return $order;
    }
}