<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;


use App\Components\ADManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class ADController extends Controller
{


    /*
     * 获取首页广告图
     *
     *
     * By TerryQi
     *
     * 2017-11-21
     *
     */
    public function getADs(Request $request)
    {
        $ads = ADManager::getADs();
        return ApiResponse::makeResponse(true, $ads, ApiResponse::SUCCESS_CODE);
    }

    public function getADsInfo(Request $request){
        $data = $request->all();

        $ads = ADManager::getADsInfo($data['id']);
        return ApiResponse::makeResponse(true, $ads, ApiResponse::SUCCESS_CODE);
    }

}