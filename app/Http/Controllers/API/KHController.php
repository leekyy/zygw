<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;


use App\Components\HRManager;
use App\Components\KHManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class KHController extends Controller
{


    /*
     * 获取所有报备过的客户信息
     *
     *
     * By TerryQi
     *
     * 2017-11-22
     *
     */
    public function getKHs(Request $request)
    {
        $hrs = KHManager::getKHs();
        return ApiResponse::makeResponse(true, $hrs, ApiResponse::SUCCESS_CODE);
    }

    /*根据客户的状态和客户名字搜索客户
     *
     * By yinyue
     * 2017-12-12
     */

    public  function  getSearchKh(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
//            'visitingstate' => 'required',
//            'kehu_name' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hr = KHManager::getSearchKh($data);
        return ApiResponse::makeResponse(true, $hr, ApiResponse::SUCCESS_CODE);
    }


    public function  getBKH(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
//            'visitingstate' => 'required',
//            'kehu_name' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hr = KHManager::getBKH($data);
        return ApiResponse::makeResponse(true, $hr, ApiResponse::SUCCESS_CODE);
    }

    /*修改客户资料
     * By yinyue
     * 2017-12-14
     *
     */

    public  function  getXKH(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
          'id' => 'required',
//            'kehu_name' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hr = KHManager::getXKH($data);
        return ApiResponse::makeResponse(true, $hr, ApiResponse::SUCCESS_CODE);
    }

    /*By yinyue
     *获取通知消息
     *
     * 2017-12-5
     */

    public  function  getNEWs(){
        $hrs = KHManager::getNEWs();
        return $hrs;
    }

    /*
        * 根据客户id获取客户详细信息
        *
        *
        * By TerryQi
        *
        * 2017-11-22
        *
        */
    public function getKHById(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
            'id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $kh = KHManager::getKHById($data);

//      $hrs = HByIdManager::getHById($id);

        return ApiResponse::makeResponse(true, $kh, ApiResponse::SUCCESS_CODE);
    }
//
//    //根据指定的房源小区id获得指定的楼盘参数
//    public function getHDById(Request $request){
//        $data = $request->all();
//        $requestValidationResult = RequestValidator::validator($data, [
//            'id' => 'required',
//        ]);
//        if (!$requestValidationResult) {
//            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
//        }
//        $hrs = HRManager::getHDById($data['id']);
//        return ApiResponse::makeResponse(true, $hrs, ApiResponse::SUCCESS_CODE);
//
//    }
//
//    //根据指定的小区id获得相对应的户型推荐
//    public function  getHXById(Request $request){
//        $data = $request->all();
//        $requestValidationResult = RequestValidator::validator($data, [
//            'house_id' => 'required',
//        ]);
//        if (!$requestValidationResult) {
//            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
//        }
//        $hx = HRManager::getHXById($data['house_id']);
//
//        return ApiResponse::makeResponse(true, $hx, ApiResponse::SUCCESS_CODE);
//    }
//
//    //根据指定的小区获取相对应的用户评论
//     public function  getHCById(Request $request){
//        $data = $request->all();
//         $requestValidationResult = RequestValidator::validator($data, [
//             'house_id' => 'required',
//         ]);
//         if (!$requestValidationResult) {
//             return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
//         }
//         $hc = HRManager::getHCById($data['house_id']);
//         return ApiResponse::makeResponse(true, $hc, ApiResponse::SUCCESS_CODE);
//     }



}