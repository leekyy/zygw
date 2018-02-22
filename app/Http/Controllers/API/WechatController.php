<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2018/1/11
 * Time: 9:43
 */

namespace App\Http\Controllers\API;


use App\Components\DateTool;
use App\Components\MemberManager;
use App\Components\SendMessageManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\WechatManager;
use App\Http\Controllers\Controller;
use App\Models\MemberOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Log;
use Yansongda\Pay\Pay;


class WechatController extends Controller
{

    /*
     * 获取小程序登录信息
     *
     * By TerryQi
     *
     */
    public function miniProgramLogin(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'code' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $app = app('wechat.mini_program');
        $result = $app->auth->session($data['code']);
        return ApiResponse::makeResponse(true, $result, ApiResponse::SUCCESS_CODE);
    }


    //微信服务号Server

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');

        $app->server->push(function ($message) {
            Log::info(\GuzzleHttp\json_encode($message));
            $from_user = $message['FromUserName'];  //消息来自于哪个用户
            Log::info("from_user:" . $from_user);
            /*
             * 每次接收用户信息都需要获取uniondid
             *
             * By TerryQi
             *
             * 2018-02-22
             */
            $user = UserManager::getByFWHOpenId($from_user);
            Log::info("user:" . json_encode($user));
            if (!$user) {  //若不存在用户，则应该走注册流程
                $user = WechatManager::getUserInfoByFWHOpenId($from_user);
                UserManager::registerFWH($user);
            }
            switch ($message['MsgType']) {
                case 'event':
                    //进行用户注册流程
                    return '欢迎使用置业顾问';
                    break;
                case 'text':
                    return '欢迎使用置业顾问';
                    break;
                case 'image':
                    return '欢迎使用置业顾问';
                    break;
                case 'voice':
                    return '欢迎使用置业顾问';
                    break;
                case 'video':
                    return '欢迎使用置业顾问';
                    break;
                case 'location':
                    return '欢迎使用置业顾问';
                    break;
                case 'link':
                    return '欢迎使用置业顾问';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });
        $response = $app->server->serve();
        return $response;
    }


    //服务号，根据服务号openid获取用户信息
    public function getUserInfoByFWHOpenId(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'fwh_openid' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $userInfo = WechatManager::getUserInfoByFWHOpenId($data['fwh_openid']);
        return ApiResponse::makeResponse(true, $userInfo, ApiResponse::SUCCESS_CODE);
    }

}