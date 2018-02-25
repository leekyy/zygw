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
use App\Components\RecommInfoManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\RecommInfo;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class RecommController extends Controller
{

    /*
     * 推荐接口
     *
     * By TerryQi
     *
     * 2018-02-25
     */
    public function recommUser(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
            're_user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //推荐用户信息是否存在
        if (!UserManager::getById($data['re_user_id'])) {
            return ApiResponse::makeResponse(false, '推荐人信息不存在', ApiResponse::INNER_ERROR);
        }
        if (RecommInfoManager::isUserHasBeenRecommended($data['user_id'])) {
            return ApiResponse::makeResponse(false, "该用户已经被推荐过", ApiResponse::INNER_ERROR);
        }

        $recommInfo = new RecommInfo();
        $recommInfo = RecommInfoManager::setInfo($recommInfo, $data);
        $recommInfo->save();
        return ApiResponse::makeResponse(true, $recommInfo, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据推荐用户id获取被推荐人信息
     *
     * BY TerryQi
     *
     * 2018-02-25
     *
     */
    public function getListByReUserId(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        $re_user_id = $data['user_id'];
        $recommInfos = RecommInfoManager::getListByReUserId($re_user_id);
        foreach ($recommInfos as $recommInfo) {
            $recommInfo = RecommInfoManager::getInfoByLevel($recommInfo, '0');
        }
        return ApiResponse::makeResponse(true, $recommInfos, ApiResponse::SUCCESS_CODE);
    }

}