<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\DateTool;
use App\Components\GoodsExchangeManager;
use App\Components\GoodsManager;
use App\Components\HomeManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\GoodsExchange;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class GoodsController extends Controller
{

    /*
     * 获取商品列表
     *
     * By TerryQi
     *
     * 2017-11-27
     */
    public function getGoodsList(Request $request)
    {
        $goodses = GoodsManager::getListByStatusPaginate(['1']);
        return ApiResponse::makeResponse(true, $goodses, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据id获取轮播图信息
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     *
     */
    public function getGoodsById(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $goods = GoodsManager::getById($data['id']);
        return ApiResponse::makeResponse(true, $goods, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 兑换商品
     *
     * By TerryQi
     *
     * 2018-01-22
     */
    public function exchange(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
            'goods_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $user = UserManager::getUserInfoByIdWithToken($data['user_id']);    //带token
        $goods = GoodsManager::getById($data['goods_id']);
        //判断用户积分余额是否足够
        $user_jifen = $user->jifen;
        $goods_jifen = $goods->jifen;
        if ($user_jifen < $goods_jifen) {
            return ApiResponse::makeResponse(false, "积分值不足，支付失败", ApiResponse::INNER_ERROR);
        }
        //扣减用户积分
        $user->jifen = $user_jifen - $goods_jifen;
        $user->save();
        $user = UserManager::getUserInfoById($user->id);    //返回用户基本信息
        //新建兑换订单
        $goodsExchange = new GoodsExchange();
        $goodsExchange->user_id = $user->id;
        $goodsExchange->goods_id = $goods->id;
        $goodsExchange->total_jifen = $goods->jifen;
        $goodsExchange->dh_time = DateTool::getCurrentTime();
        $goodsExchange->save();
        return ApiResponse::makeResponse(true, $goodsExchange, ApiResponse::SUCCESS_CODE);

    }

    /*
     * 根据用户id获取兑换订单列表
     *
     * By TerryQi
     *
     * 2018-01-22
     */
    public function getExchangeListByUserId(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $goodsExchanges = GoodsExchangeManager::getListByUserId($data['user_id']);
        foreach ($goodsExchanges as $goodsExchange) {
            $goodsExchange = GoodsExchangeManager::getInfoByLevel($goodsExchange, 0);
        }
        return ApiResponse::makeResponse(true, $goodsExchanges, ApiResponse::SUCCESS_CODE);
    }
}