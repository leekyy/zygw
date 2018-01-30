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
use App\Components\HouseClientManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\House;
use App\Models\Doctor;
use App\Models\HouseClient;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class HouseClientController
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
        //获取房源列表
        $houseClients = HouseClientManager::getListByHouseIdPaginate($house_id);
        $upload_token = QNManager::uploadToken();
        return view('admin.houseClient.index', ['admin' => $admin, 'datas' => $houseClients,
            'house' => $house, 'upload_token' => $upload_token]);
    }


    //删除楼盘
    public function del(Request $request, $id)
    {
        //房产商客户id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $houseClient = HouseClient::find($id);
        $houseClient->delete();
        // $houseClient =$request->all()['search'];
        $data = $request->all();
        return redirect('/admin/houseClient/index?house_id=' . $data['house_id']);
    }

    //新建或编辑房产商客户->post
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'phonenums' => 'required',
            'house_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $house_client_arr = explode(",", $data['phonenums']);
        foreach ($house_client_arr as $item) {
            $houseClient = new HouseClient();
            $houseClient->house_id = $data['house_id'];
            $houseClient->admin_id = $data['admin_id'];
            $houseClient->phonenum = $item;
            $houseClient->save();
        }
        return redirect('/admin/houseClient/index?house_id=' . $data['house_id']);
    }
}