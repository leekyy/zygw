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
use App\Components\ZYGWManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\House;
use App\Models\Doctor;
use App\Models\ZYGW;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class ZYGWController
{
    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'house_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //获取楼盘基本信息
        $house_id = $data['house_id'];
        $house = HouseManager::getById($house_id);
        $house = HouseManager::getHouseInfoByLevel($house, "0");
        //获取产品列表
        $zygws = ZYGWManager::getListByHouseId($house_id);
        $upload_token = QNManager::uploadToken();
        return view('admin.zygw.index', ['admin' => $admin, 'datas' => $zygws
            , 'house' => $house, 'upload_token' => $upload_token]);
    }


    //删除楼盘
    public function del(Request $request, $id)
    {
        //广告位id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $zygw = ZYGW::find($id);
        $zygw->delete();
        // $zygw =$request->all()['search'];
        $data = $request->all();
        return redirect('/admin/zygw/index?house_id=' . $data['house_id']);
    }


    //新建或编辑楼盘-get
    public function edit(Request $request)
    {
        $data = $request->all();
        $zygw = new ZYGW();
        if (array_key_exists('id', $data)) {
            $zygw = ZYGW::find($data['id']);
        }
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.huxing.edit', ['admin' => $admin, 'data' => $zygw, 'upload_token' => $upload_token]);
    }


    //新建或编辑置业顾问->post
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $zygw = new ZYGW();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $zygw = ZYGW::find($data['id']);
        }
        $zygw = ZYGWManager::setZYGW($zygw, $data);
        $zygw->save();
        return redirect('/admin/zygw/index?house_id=' . $zygw->house_id);
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
            $house = ZYGWManager::getListByStatusPaginate(["0", "1",]);
        } else {
            $house = ZYGWManager::getListByStatusPaginate([$data['search_status']]);
        }
        return view('admin.zygw.getHouseById', ['admin' => $admin, 'datas' => $house]);
    }

    /*
     * 根据id获取顾问信息
     *
     */
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
        $zygw = ZYGWManager::getById($data['id']);
        return ApiResponse::makeResponse(true, $zygw, ApiResponse::SUCCESS_CODE);

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
        $house = ZYGWManager::getHouseById($data['house_id']);
        $upload_token = QNManager::uploadToken();
        return view('admin.zygw.getHouseById', ['admin' => $admin, 'datas' => $house, 'upload_token' => $upload_token]);

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
        $zygw = ZYGWManager::getById($id);
        $zygw->status = $opt;
        $zygw->save();
        return redirect('/admin/zygw/index?house_id=' . $zygw->house_id);
    }
}