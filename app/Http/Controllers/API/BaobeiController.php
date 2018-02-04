<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\ADManager;
use App\Components\BaobeiManager;
use App\Components\ClientManager;
use App\Components\DateTool;
use App\Components\HomeManager;
use App\Components\HouseClientManager;
use App\Components\HouseManager;
use App\Components\SendMessageManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\Baobei;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class BaobeiController extends Controller
{

    /*
     * 获取客户报备配置相关信息
     *
     * By TerryQi
     *
     * 2017-11-27
     */
    public function getBaobeiOption(Request $request)
    {
        $baobeiOption = BaobeiManager::getBaobeiOptions();
        return ApiResponse::makeResponse(true, $baobeiOption, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 中介/案场负责人进行客户报备
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public function baobeiClient(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'name' => 'required',
            'phonenum' => 'required',
            'house_id' => 'required',
            'user_id' => 'required',
            'visit_way' => 'required',
            'plan_visit_time' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //第一步，建立用户信息，通过手机号判断客户是否存在，用于获取client_id
        $client = ClientManager::getByPhonenum($data['phonenum']);
        $user = UserManager::getUserInfoById($data['user_id']);
        $house = HouseManager::getById($data['house_id']);
        if (!$client) { //如果不存在客户，需要建立客户信息
            $client = new Client();
            $client = ClientManager::setClient($client, $data);
            $client->save();
            $client = ClientManager::getById($client->id);
        }
        ClientManager::addBaobeiTimes($client->id); //报备次数加1
        //第二步，判断客户的有效性
        //1）客户是否是为房地产商的客户
        if (HouseClientManager::isClientAsDeveloperClient($client->phonenum, $house->id)) {
            $client->remark = "房产商客户 " . $house->title;
            $client->save();
            return ApiResponse::makeResponse(false, '客户为房产商客户', ApiResponse::INNER_ERROR);
        }
        //2）客户是否已经报备过
        if (BaobeiManager::isClientAlreadyBaobeiByHouseId($client->id, $house->id)) {
            return ApiResponse::makeResponse(false, '客户已经在该楼盘报备', ApiResponse::INNER_ERROR);
        }
        //3）用户是否为案场负责人，如果是案场负责人的话，是否对自己的楼盘进行报备
        if (UserUpManager::isUserAlreadyACFZ($user->id, $house->id)) {
            return ApiResponse::makeResponse(false, '该用户为楼盘案场负责人', ApiResponse::INNER_ERROR);
        }
        //进行客户信息报备
        $baobei = new Baobei();
//        dd($data);
        $baobei = BaobeiManager::setBaoBei($baobei, $data);
        $baobei->client_id = $client->id;
        $baobei->trade_no = Utils::generateTradeNo();   //生成报备流水
        $baobei->save();
        $baobei = BaobeiManager::getById($baobei->id);  //保存报备信息
        //向案场负责人发送消息
        $acfzrs = UserManager::getValidACFZRsByHouseId($house->id);
        foreach ($acfzrs as $acfzr) {
            $message_content = [
                'keyword1' => $client->name . substr($client->phonenum, -4),
                'keyword2' => $baobei->plan_visit_time,
                'keyword3' => $house->title,
                'keyword4' => BaobeiManager::getVisitWayTxt($baobei->visit_way)];
            SendMessageManager::sendMessage($acfzr->id, SendMessageManager::CLIENT_COMMING, $message_content);
        }
        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 客户到访
     *
     * By TerryQi
     *
     * 2018-02-03
     *
     */
    public function daofang(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
            'visit_attach' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $baobei = BaobeiManager::getById($data['id']);
        $baobei->visit_attach = $data['visit_attach'];
        $baobei->visit_time = DateTool::getCurrentTime();
        $baobei->save();
        $baobei = BaobeiManager::getById($baobei->id);
        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }
}