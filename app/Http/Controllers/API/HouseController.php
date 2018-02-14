<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\ADManager;
use App\Components\HomeManager;
use App\Components\HouseManager;
use App\Components\HuxingManager;
use App\Components\HouselabelManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\ZYGWManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class HouseController extends Controller
{

    /*
     * 根据id获取楼盘信息
     *
     * By TerryQi
     *
     * 2018-02-07
     *
     */
    public function getById(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $house = HouseManager::getById($data['id']);
        $house = HouseManager::getHouseInfoByLevel($house, "01");
        return ApiResponse::makeResponse(true, $house, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 根据条件搜索列表
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public function searchByCon(Request $request)
    {
        $data = $request->all();
        //根据条件搜索楼盘
        $houses = HouseManager::searchByConValid($data);
        $level = "0";
        if (array_key_exists('level', $data)) {
            $level = $data['level'];
        }
        foreach ($houses as $house) {
            $house = HouseManager::getHouseInfoByLevel($house, $level);
        }
        return ApiResponse::makeResponse(true, $houses, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 根据search_name获取楼盘列表
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public function searchByName(Request $request)
    {
        $data = $request->all();
        $search_word = "";
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        //根据关键词搜索楼盘
        $houses = HouseManager::searchByNameValid($data['search_word']);
        foreach ($houses as $house) {
            $house = HouseManager::getHouseInfoByLevel($house, '0');
        }
        return ApiResponse::makeResponse(true, $houses, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 获取该楼盘下所有的户型
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public function getHuxingsByHouseId(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'house_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $huxings = HuxingManager::getListByHouseIdValid($data['house_id']);
        foreach ($huxings as $huxing) {
            $huxing = HuxingManager::getInfoByLevel($huxing, '0');
        }
        return ApiResponse::makeResponse(true, $huxings, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 获取楼盘下的置业顾问列表
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public function getZYGWsByHouseId(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'house_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $zygws = ZYGWManager::getListByHouseIdValid($data['house_id']);
        return ApiResponse::makeResponse(true, $zygws, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 获取楼盘相关全部属性
     *
     * By TerryQi
     *
     * 2018-02-03
     *
     */
    public function getOptions(Request $request)
    {
        $options = HouseManager::getOptions();
        return ApiResponse::makeResponse(true, $options, ApiResponse::SUCCESS_CODE);
    }


}