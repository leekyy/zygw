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
use Illuminate\Support\Facades\DB;

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
    public static function getListPaginate()
    {
        $goodsExchanges = GoodsExchange::orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $goodsExchanges;
    }
    public static function getListByStatusPaginate($status)
    {
        $userUps = GoodsExchange::wherein('status', $status)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        //设置用户信息和楼盘信息
        return $userUps;
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
     * 获取总订单数
     *
     * By TerryQi
     */
    public static function getAllQDRenCiShuNum()
    {
        $count = GoodsExchange::all()->count();
        return $count;
    }


    /*
    * 获取订单总人数
    *
    * By TerryQi
    */
    public static function getAllQDRenShuNum()
    {
        $count = DB::select('SELECT COUNT(distinct user_id) as rs FROM zygwdb.t_goods_exchange;', []);
        return $count[0]->rs;
    }


    /*
     * 获取订单兑换的总积分
     *
     * By TerryQi
     *
     */
    public static function getAllPaiSongJiFenNum()
    {
        $count = DB::select('SELECT SUM(total_jifen)  as jf FROM zygwdb.t_goods_exchange;', []);
        return $count[0]->jf;
    }

    /*
    * 获取近N日的报表
    *
    * By TerryQi
    *
    */
    public static function getRecentDatas($day_num)
    {
        $data = DB::select('SELECT DATE_FORMAT( created_at, "%Y-%m-%d" ) as tjdate , COUNT(*)  as qdrs, SUM(total_jifen)  as psjfs FROM zygwdb.t_goods_exchange GROUP BY tjdate order by tjdate desc limit 0,:day_num;', ['day_num' => $day_num]);
        return $data;
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

//goodsexchange(index.php)
    public static function getUserUpInfoByLevel($userUp, $level)
    {
        $userUp->user = UserManager::getUserInfoById($userUp->user_id);
        $userUp->goods = GoodsManager::getById($userUp->goods_id);
        $userUp->admin = AdminManager::getAdminInfoById($userUp->admin_id);
        return $userUp;
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