<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\HomeManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class UserController extends Controller
{

    const APPID = "wxd3a3b21f912b6c89";
    const APPSECRET = "23e85428d04d7507377a5a01960d074e";

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQiniuToken(Request $request)
    {
        $accessKey = 'JXanCoTnAoyJd4WclS-zPhA8JmWooPTqvK5RCHXb';
        $secretKey = 'ouc-dLEY42KijHeUaTzTBzFeM2Q1mKk_M_3vNpmT';

        $auth = new Auth($accessKey, $secretKey);

        $bucket = 'dsyy';
        $upToken = $auth->uploadToken($bucket);

        return ApiResponse::makeResponse(true, $upToken, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 通过code换取open_id和session_key
     *
     * By TerryQi
     *
     * 2017-10-08
     */
    public function getXCXOpenId(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'code' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $code = $data['code'];  //获取小程序code
        //触发后端
        $ret_str = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid=" . self::APPID . "&secret=" . self::APPSECRET . "&js_code=" . $code . "&grant_type=authorization_code");//通过code换取网页授权access_token

        $ret_val = json_decode($ret_str, true);

        return ApiResponse::makeResponse(true, $ret_val, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 用户登录
     *
     * @request 用户相关信息
     *
     * By TerryQi
     *
     */
    public function login(Request $request)
    {
        $data = $request->all();    //request转array
        $user = null;          //返回user信息
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'account_type' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //如果是小程序
        if ($data['account_type'] == 'xcx') {
            //合规校验account_type
            $requestValidationResult = RequestValidator::validator($request->all(), [
                'xcx_openid' => 'required',
            ]);
            if ($requestValidationResult !== true) {
                return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
            }
            //进行注册
            $user = UserManager::login($data);
        }
        //如果注册失败，返回失败
        if ($user == null) {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::REGISTER_FAILED], ApiResponse::REGISTER_FAILED);
        } else {
            return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
        }
    }

    /*
     * 根据id更新用户信息
     *
     * @request id:用户id
     *
     * By TerryQi
     *
     */
    public function updateUserById(Request $request)
    {
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $data = $request->all();
        $user = UserManager::updateUser($data);
        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 根据id获取用户信息
     *
     * @request id：用户id
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public function getUserById(Request $request)
    {
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $data = $request->all();
        $user = UserManager::getUserInfoById($data['user_id']);
        if ($user) {
            return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::NO_USER], ApiResponse::NO_USER);
        }
    }

    /*获取用户的佣金
     *
     * By yinyue
     * 2017-12-5
     */

    public function getUserYongjin(Request $request)
    {
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $data = $request->all();
        $user = UserManager::getUserYongjin($data['user_id']);
        if ($user) {
            return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::NO_USER], ApiResponse::NO_USER);
        }

    }

//获取积分商品
    public function getGoods()
    {
        $user = UserManager::getGoods();
        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }


    public function getGoodById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
            'user_id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $kh = UserManager::getGoodById($data);

//      $hrs = HByIdManager::getHById($id);

        return ApiResponse::makeResponse(true, $kh, ApiResponse::SUCCESS_CODE);
    }

    //获取用户的积分兑换记录
    public function getExchange(Request $request)
    {


        $requestValidationResult = RequestValidator::validator($request->all(), []);

        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        $data = $request->all();


        $user = UserManager::getExchange($data);
        // var_dump();
        // exit;
        if ($user) {
            return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::NO_USER], ApiResponse::NO_USER);
        }
    }


    public function getUserJifen(Request $request)
    {


        $requestValidationResult = RequestValidator::validator($request->all(), []);

        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        $data = $request->all();


        $rs = UserManager::getUserJifen($data);
        // var_dump();
        // exit;
        if ($rs['code']) {
            return ApiResponse::makeResponse(true, $rs['user'], ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, $rs['user'], ApiResponse::NO_USER);
        }
    }


    //获取合作细则
    //By yinue
    //2017-12-26
    public function getHezuo()
    {
        $hezuo = UserManager::getHezuo();
        return ApiResponse::makeResponse(true, $hezuo, ApiResponse::SUCCESS_CODE);
    }

    public function getWhitebook()
    {
        $hezuo = UserManager::getWhitebook();
        return ApiResponse::makeResponse(true, $hezuo, ApiResponse::SUCCESS_CODE);
    }


    //获取积分规则
    public function getRules()
    {
        $rules = UserManager::getRules();
        return ApiResponse::makeResponse(true, $rules, ApiResponse::SUCCESS_CODE);
    }
    //获取客户的成交的房贷计算器
//    public function getJisuanqi(){
//        $hrs = UserManager::getJisuanqi();
//        return ApiResponse::makeResponse(true, $hrs, ApiResponse::SUCCESS_CODE);
//    }

//获取客户对我的接待评价
    public function getPingjia(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
//           'user_id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hrs = UserManager::getPingjia();

//      $hrs = HByIdManager::getHById($id);

        return ApiResponse::makeResponse(true, $hrs, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据id获取用户信息带token
     *
     * @request id：用户id
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public function getUserInfoByIdWithToken(Request $request)
    {
        $requestValidationResult = RequestValidator::validator($request->all(), [
//            'user_id' => 'requ//            'token'=>'required',ired',

        ]);

        $data = $request->all();

        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        $user = UserManager::getUserInfoByIdWithToken($data);
        if ($user) {
            return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::NO_USER], ApiResponse::NO_USER);
        }
    }

}