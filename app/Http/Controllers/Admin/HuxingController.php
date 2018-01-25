<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\ADManager;
use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Components\HouseManager;
use App\Components\HuxingManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\House;
use App\Models\Doctor;
use App\Models\Huxing;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class HuxingController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $house = HouseManager::getListPaginate();
//        dd($userUps);
        $upload_token = QNManager::uploadToken();
        return view('admin.house.index', ['admin' => $admin, 'datas' => $house, 'upload_token' => $upload_token]);
    }


    //删除楼盘
    public function del(Request $request, $id)
    {

        //广告位id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $ad = Huxing::find($id);
        $ad->delete();
       // $huxing =$request->all()['search'];
        $data=$request->all();
        return redirect('/admin/house/getHouseById?house_id='.$data['house_id']);
    }


    //新建或编辑楼盘-get
    public function edit(Request $request)
    {
        $data = $request->all();
        $huxing = new Huxing();
        if (array_key_exists('id', $data)) {
            $huxing = Huxing::find($data['id']);
        }
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.huxing.edit', ['admin' => $admin, 'data' => $huxing, 'upload_token' => $upload_token]);
    }


    //新建或编辑楼盘->post
    public function editPost(Request $request)
    {

        $data = $request->all();
//        dd($data);
        $house = new Huxing();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $house = Huxing::find($data['id']);
        }
        $house = HuxingManager::setHuxing($house, $data);
        $house->save();
        $house_id=0;
        if (array_key_exists('house_id', $data)) {
            $house_id = array_get($data, 'house_id');
        }
        return redirect('/admin/house/getHouseById?house_id='.$house_id);
    }

    //进行楼盘的搜索
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $house = null;
//        dd($data['search_status']);
        //如果不存在search_status，代表搜索全部
        if (!array_key_exists('search_status', $data) || Utils::isObjNull($data['search_status'])) {
            $house = HuxingManager::getListByStatusPaginate(["0", "1",]);
        } else {
            $house = HuxingManager::getListByStatusPaginate([$data['search_status']]);
        }
        return view('admin.house.getHouseById', ['admin' => $admin, 'datas' => $house]);
    }


    public function getById(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $admin = HuxingManager::getById($data['id']);
        return ApiResponse::makeResponse(true, $admin, ApiResponse::SUCCESS_CODE);

    }

    public function getHouseById(Request $request)
    {

        $admin = $request->session()->get('admin');
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'house_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $house = HuxingManager::getHouseById($data['house_id']);
        $upload_token = QNManager::uploadToken();
        return view('admin.house.getHouseById', ['admin' => $admin, 'datas' => $house, 'upload_token' => $upload_token]);

    }

    //设置状态
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'opt' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //opt必须为0或者1
        $opt = $data['opt'];
        if (!($opt == '0' || $opt == '1')) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数,opt必须为0或者1，现值为' . $opt]);
        }
        $house = Huxing::where('id', '=', $id)->first();
        $house->status = $opt;
        $house->save();
        return redirect('/admin/house/getHouseById');
    }


//    //设置状态
//    public function setStatus(Request $request, $id)
//    {
//        $data = $request->all();
//        //合规校验
//        $requestValidationResult = RequestValidator::validator($request->all(), [
//            'status' => 'required',
//        ]);
//        if ($requestValidationResult !== true) {
//            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
//        }
//        //opt必须为0或者1
//        $status = $data['status'];
//        if (!($status == '1' || $status == '2')) {
//            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数,opt必须为0或者1，现值为' . $opt]);
//        }
//        $house = HouseManager::getById($id);
//        $house->status = $status;
//        $house->admin_id = $data['admin_id'];
////        $userUp->sh_time = DateTool::getCurrentTime();
//        $house->save();
//        return redirect('/admin/house/index');
//    }
}