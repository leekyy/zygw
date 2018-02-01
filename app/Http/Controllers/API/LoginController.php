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
use App\Components\LoginManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class LoginController extends Controller
{


    /*
     * 登录
     *
     *
     * By yinyue
     *
     * 2017-12-4
     *
     */
    public function enter(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
            'nick_name' => 'required',
            'phonenum' => 'required',
            'email' => 'required',
            'cardID' => 'required'
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hrs = LoginManager::enter($data);
        return ApiResponse::makeResponse(true, $hrs, ApiResponse::SUCCESS_CODE);
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
//    //根据指定的产品小区id获得指定的楼盘参数
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