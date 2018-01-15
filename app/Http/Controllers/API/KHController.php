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

    /*2017-1-15
     * By Yinyue
     * 在客户列表页可以根据楼盘搜索客户
     */
    public  function  getSearchKhs(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
          ' intent'=>'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hr = KHManager::getSearchKhs($data);
        return ApiResponse::makeResponse(true, $hr, ApiResponse::SUCCESS_CODE);
    }

    public  function  getKhIntent(Request $request){
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
           // ' intent'=>'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $hr = KHManager::getKhIntent();
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



}