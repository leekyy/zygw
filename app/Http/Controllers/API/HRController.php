<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;


use App\Components\HRManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class HRController extends Controller
{


    /*
     * 获取房源信息
     *
     *
     * By TerryQi
     *
     * 2017-11-22
     *
     */
    public function getHRs(Request $request)
    {
        $hrs = HRManager::getHRs();
        return ApiResponse::makeResponse(true, $hrs, ApiResponse::SUCCESS_CODE);
    }
  /*搜索小区
   *
   * By yinyue
   * 2017-12-11
   */
    public function getSearch(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
//            'type_name' => 'required',
            'title' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hr = HRManager::getSearch($data);
        return ApiResponse::makeResponse(true, $hr, ApiResponse::SUCCESS_CODE);
    }

//    public function getHouse(Request $request)
//    {
//        $data = $request->all();
//        $requestValidationResult = RequestValidator::validator($data, [
//            'title' => 'required',
//        ]);
//        if (!$requestValidationResult) {
//            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
//        }
//        $hr = HRManager::getHouse($data);
//        return ApiResponse::makeResponse(true, $hr, ApiResponse::SUCCESS_CODE);
//    }


    /*根据区域 面积 价钱搜索小区
     * By yinyue
     * 2017-12-12
     */

    public  function  getSearchHr(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
//            'visitingstate' => 'required',
//            'kehu_name' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hr = HRManager::getSearchHr($data);
        return ApiResponse::makeResponse(true, $hr, ApiResponse::SUCCESS_CODE);
    }



    /*
        * 获取房源信息
        *
        *
        * By TerryQi
        *
        * 2017-11-22
        *
        */
    public function getHRById(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
            'id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hrs = HRManager::getHRById($data);

//      $hrs = HByIdManager::getHById($id);

        return ApiResponse::makeResponse(true, $hrs, ApiResponse::SUCCESS_CODE);
    }

    //根据指定的房源小区id获得指定的楼盘参数
    public function getHDById(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
            'id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hrs = HRManager::getHDById($data['id']);
        return ApiResponse::makeResponse(true, $hrs, ApiResponse::SUCCESS_CODE);

    }

    //根据指定的小区id获得相对应的户型推荐
    public function  getHXById(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
            'house_id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hx = HRManager::getHXById($data['house_id']);

        return ApiResponse::makeResponse(true, $hx, ApiResponse::SUCCESS_CODE);
    }

    //根据指定的小区获取相对应的用户评论
     public function  getHCById(Request $request){
        $data = $request->all();
         $requestValidationResult = RequestValidator::validator($data, [
            // 'house_id' => 'required',
         ]);
         if (!$requestValidationResult) {
             return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
         }
         $hc = HRManager::getHCById($data);
         return ApiResponse::makeResponse(true, $hc, ApiResponse::SUCCESS_CODE);
     }

      public function  getHouseReview(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
            //'user_id' => 'required',
//            'kehu_name' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hr = HRManager::getHouseReview($data);
        return ApiResponse::makeResponse(true, $hr, ApiResponse::SUCCESS_CODE);
    }




}