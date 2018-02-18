<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\HomeManager;
use App\Components\HouseManager;
use App\Components\SystemManager;
use App\Components\UserManager;
use App\Components\UserQDManager;
use App\Components\UserUpManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\JifenChangeRecord;
use App\Models\UserQD;
use App\Models\UserUp;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class UserQDController extends Controller
{


    /*
     * 用户今日签到
     *
     * By TerryQi
     *
     * 2017-01-21
     */
    public function userQDToday(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //该用户是否今日签到了
        $userQD = UserQDManager::isUserAlreadyQDToday($data['user_id']);
        if ($userQD) {
            return ApiResponse::makeResponse(false, "今日已经签到", ApiResponse::INNER_ERROR);
        }
        $userQD = new UserQD();
        $userQD = UserQDManager::setUserQD($userQD, $data);
        $userQD->jifen = SystemManager::getSystemInfo()->qd_jifen;
        $userQD->save();
        //增加用户积分
        $user = UserManager::getByIdWithToken($userQD->user_id);
        $user->jifen = $user->jifen + $userQD->jifen;
        $user->save();
        //写入记录
        $jifen_change_record = new JifenChangeRecord();
        $jifen_change_record->user_id = $user->id;
        $jifen_change_record->jifen = $userQD->jifen;
        $jifen_change_record->record = "每日签到奖励";
        $jifen_change_record->save();

        //保存用户签到信息
        $userQD = UserQDManager::getUserQDById($userQD->id);
        return ApiResponse::makeResponse(true, $userQD, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 获取案场负责人所属楼盘列表
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public function getUserQDsByUserId(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $userQDs = UserQDManager::getQDListByUserIdPaginate($data['user_id']);
        return ApiResponse::makeResponse(true, $userQDs, ApiResponse::SUCCESS_CODE);
    }

}