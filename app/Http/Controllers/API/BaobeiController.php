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
use App\Components\HuxingManager;
use App\Components\SendMessageManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Components\Utils;
use App\Http\Controllers\Admin\UserACFZRController;
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
     * 获取待接收客户的列表，根据案场负责人id获取其所属的楼盘下全部有效的anchang_id为空的baobei_status==0的信息
     *
     * By TerryQi
     *
     * 2018-2-9
     */
    public function getWaitingForAcceptListByAnchangId(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //获取用户报备信息
        $userUps = UserUpManager::getUserUpHousesByUserId($data['user_id']);
        $house_ids = array();
        foreach ($userUps as $userUp) {
            array_push($house_ids, $userUp->house_id);      //案场负责人所属楼盘id数组
        }
        $baobeis = BaobeiManager::getWaitingForAccpectByHouseIds($house_ids);
        foreach ($baobeis as $baobei) {
            $baobei = BaobeiManager::getInfoByLevel($baobei, "0");
        }
        return $baobeis;
    }


    /*
     * 根据id获取报备信息详情
     *
     * By TerryQi
     *
     * 2018-02-09
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
        $baobei = BaobeiManager::getById($data['id']);
        $baobei = BaobeiManager::getInfoByLevel($baobei, "0");
        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 设置报备通用信息
     *
     * By TerryQi
     *
     * 2018-02-04
     *
     */
    public function setNormalInfo(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //获取报备基本信息
        $baobei = BaobeiManager::getById($data['id']);
        //存在报备信息
        if ($baobei) {
            //获取楼盘案场负责人
            $acfzrs = UserManager::getValidACFZRsByHouseId($baobei->house_id);
            //用户是报备楼盘的案场负责人
            if (!UserManager::isUserInACFZRs($data['user_id'], $acfzrs)) {
                return ApiResponse::makeResponse(false, '非楼盘案场负责人，无法接收客户', ApiResponse::INNER_ERROR);
            }
            $baobei = BaobeiManager::setBaoBei($baobei, $data);
            $baobei->save();
            $baobei = BaobeiManager::getById($baobei->id);
            return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, '未找到报备楼盘', ApiResponse::INNER_ERROR);
        }
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
        $baobei->user_id = $data['user_id'];
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
            'user_id' => 'required',
            'visit_attach' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //判断状态是否正确
        $baobei = BaobeiManager::getById($data['id']);
        if (!$baobei || $baobei->baobei_status != '0') { //baobei_status==0，代表报备，下一步为到访
            return ApiResponse::makeResponse(false, '报备记录状态不正确', ApiResponse::INNER_ERROR);
        }
        //进行状态保存
        $baobei = BaobeiManager::getById($data['id']);
        $baobei->visit_attach = $data['visit_attach'];
        $baobei->visit_time = DateTool::getCurrentTime();
        $baobei->baobei_status = "1";
        //设置案场负责人
        $user = UserManager::getUserInfoById($data['user_id']);
        if ($user->role == '1') { //如果该用户是案场负责人，则要设置案场负责人
            $baobei->anchang_id = $user->id;
        }
        $baobei->save();
        $baobei = BaobeiManager::getById($baobei->id);
        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }


    /*
      * 案场负责人接收客户
      *
      * By TerryQi
      *
      * 2018-02-04
      */
    public function acceptClient(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //获取报备信息
        $baobei = BaobeiManager::getById($data['id']);
        $acfzrs = UserManager::getValidACFZRsByHouseId($baobei->house_id);
        //如果报备信息里面已经有案场负责人，则不允许接收客户
        if (!Utils::isObjNull($baobei->anchang_id)) {
            return ApiResponse::makeResponse(false, '该条报备信息已经有案场负责人', ApiResponse::INNER_ERROR);
        }
        //用户不是报备楼盘的案场负责人
        if (!UserManager::isUserInACFZRs($data['user_id'], $acfzrs)) {
            return ApiResponse::makeResponse(false, '非楼盘案场负责人，无法接收客户', ApiResponse::INNER_ERROR);
        }
        $baobei->anchang_id = $data['user_id'];
        $baobei->save();
        $baobei = BaobeiManager::getById($data['id']);
        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 报备成交信息
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public function deal(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
            'user_id' => 'required',
            'deal_size' => 'required',
            'deal_price' => 'required',
            'deal_huxing_id' => 'required',
            'pay_way_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //获取报备信息
        $baobei = BaobeiManager::getById($data['id']);
        if (!$baobei || $baobei->baobei_status != '1') { //baobei_status==1，代表到访，下一步为成交
            return ApiResponse::makeResponse(false, '报备记录状态不正确', ApiResponse::INNER_ERROR);
        }
//        $acfzrs = UserManager::getValidACFZRsByHouseId($baobei->house_id);    //2018-02-11修改逻辑，不再判断用户是否数据该楼盘案场负责人，该问该条报备记录为本人操作
        //用户不是报备楼盘的案场负责人
//        if (!UserManager::isUserInACFZRs($data['user_id'], $acfzrs)) {
        if ($baobei->anchang_id != $data['user_id']) {      //该条报备记录属于该案场负责人
            return ApiResponse::makeResponse(false, '非楼盘案场负责人，无法接收客户', ApiResponse::INNER_ERROR);
        }
        //获取佣金
        $huxing = HuxingManager::getById($data['deal_huxing_id']);
        $yongjin = 0;
        //获取佣金金额
        if ($huxing->yongjin_type == '0') { //固定金额
            $yongjin = $huxing->yongjin_value;
        }
        if ($huxing->yongjin_type == "1") {
            $yongjin = $huxing->yongjin_value * $data['deal_price'] / 1000; //成交额千分比
        }
//        dd($yongjin);
        $baobei = BaobeiManager::setBaoBei($baobei, $data);
        $baobei->yongjin = $yongjin;
        $baobei->baobei_status = '2';    //报备状态为成交
        $baobei->deal_time = DateTool::getCurrentTime();
        $baobei->save();
        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }

    /*
     *  报备签约信息
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public function sign(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //获取报备信息
        $baobei = BaobeiManager::getById($data['id']);
        if (!$baobei || $baobei->baobei_status != '2') { //baobei_status==2，代表成交，下一步为签约
            return ApiResponse::makeResponse(false, '报备记录状态不正确', ApiResponse::INNER_ERROR);
        }
        $acfzrs = UserManager::getValidACFZRsByHouseId($baobei->house_id);
        //用户不是报备楼盘的案场负责人
        if (!UserManager::isUserInACFZRs($data['user_id'], $acfzrs)) {
            return ApiResponse::makeResponse(false, '非楼盘案场负责人，无法接收客户', ApiResponse::INNER_ERROR);
        }
        $baobei->sign_time = DateTool::getCurrentTime();
        $baobei->baobei_status = "3";
        $baobei->save();
        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }

    /*
     *  报备全款到账信息
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public function qkdz(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //获取报备信息
        $baobei = BaobeiManager::getById($data['id']);
        if (!$baobei || $baobei->baobei_status != '3') { //baobei_status==3，代表签约，下一步为全款到账
            return ApiResponse::makeResponse(false, '报备记录状态不正确', ApiResponse::INNER_ERROR);
        }
        $acfzrs = UserManager::getValidACFZRsByHouseId($baobei->house_id);
        //用户不是报备楼盘的案场负责人
        if (!UserManager::isUserInACFZRs($data['user_id'], $acfzrs)) {
            return ApiResponse::makeResponse(false, '非楼盘案场负责人，无法接收客户', ApiResponse::INNER_ERROR);
        }
        $baobei->qkdz_time = DateTool::getCurrentTime();
        $baobei->baobei_status = "4";
        $baobei->save();
        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 案场负责人设置可结算状态
     *
     * By TerryQi
     *
     * 2018-02-04
     *
     */
    public function canjiesuan(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //获取报备信息
        $baobei = BaobeiManager::getById($data['id']);
        if (!$baobei || (int)$baobei->baobei_status < 2) { //baobei_status==2，代表成交，成交之后方可可以进行可结算设置
            return ApiResponse::makeResponse(false, '报备记录状态不正确', ApiResponse::INNER_ERROR);
        }
        $acfzrs = UserManager::getValidACFZRsByHouseId($baobei->house_id);
        //用户不是报备楼盘的案场负责人
        if (!UserManager::isUserInACFZRs($data['user_id'], $acfzrs)) {
            return ApiResponse::makeResponse(false, '非楼盘案场负责人，无法接收客户', ApiResponse::INNER_ERROR);
        }
        $baobei->can_jiesuan_status = "1";
        $baobei->can_jiesuan_time = DateTool::getCurrentTime();
        $baobei->save();
        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 中介维度查看各个状态的报备信息
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public function getListForZJByStatus(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //报备状态条件
        $baobei_status = null;
        if (array_key_exists('baobei_status', $data)) {
            $baobei_status = $data['baobei_status'];
        }
        //是否可以结算条件
        $can_jiesuan_status = null;
        if (array_key_exists('can_jiesuan_status', $data)) {
            $can_jiesuan_status = $data['can_jiesuan_status'];
        }
        //是否已经结算条件
        $pay_zhongie_status = null;
        if (array_key_exists('pay_zhongie_status', $data)) {
            $pay_zhongie_status = $data['pay_zhongie_status'];
        }
        $baobeis = BaobeiManager::getListForZJByStatusPaginate($data['user_id'],
            $baobei_status, $can_jiesuan_status, $pay_zhongie_status);
        foreach ($baobeis as $baobei) {
            $baobei = BaobeiManager::getInfoByLevel($baobei, '0');
        }
        return $baobeis;
    }

    /*
     * 案场负责人维度查看各个状态的报备信息
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public function getListForACByStatus(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'anchang_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //报备状态条件
        $baobei_status = null;
        if (array_key_exists('baobei_status', $data)) {
            $baobei_status = $data['baobei_status'];
        }
        //是否可以结算条件
        $can_jiesuan_status = null;
        if (array_key_exists('can_jiesuan_status', $data)) {
            $can_jiesuan_status = $data['can_jiesuan_status'];
        }
        //是否已经结算条件
        $pay_zhongie_status = null;
        if (array_key_exists('pay_zhongie_status', $data)) {
            $pay_zhongie_status = $data['pay_zhongie_status'];
        }
        $baobeis = BaobeiManager::getListForZJByStatusPaginate($data['anchang_id'],
            $baobei_status, $can_jiesuan_status, $pay_zhongie_status);
        foreach ($baobeis as $baobei) {
            $baobei = BaobeiManager::getInfoByLevel($baobei, '0');
        }
        return $baobeis;
    }
}