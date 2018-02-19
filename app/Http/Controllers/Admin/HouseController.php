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
use App\Components\BaobeiManager;
use App\Components\DateTool;
use App\Components\HouseAreaManager;
use App\Components\HouselabelManager;
use App\Components\HouseTypeManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Components\HouseManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\House;
use App\Models\HouseType;
use App\Models\Huxing;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;


class HouseController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $houses = HouseManager::getListPaginate();
        foreach ($houses as $house) {
            $house = HouseManager::getHouseInfoByLevel($house, "0");
        }
//        dd($house);
        $upload_token = QNManager::uploadToken();
        $houseTypes = HouseTypeManager::getList(); //获取楼盘类型
        $houseLabels = HouselabelManager::getList();        //获取楼盘标签
        $houseAreas = HouseAreaManager::getList();  //获取楼盘区域

        return view('admin.house.index', ['admin' => $admin, 'datas' => $houses, 'upload_token' => $upload_token
            , 'houseTypes' => $houseTypes, 'houseLabels' => $houseLabels, 'houseAreas' => $houseAreas]);
    }

    //统计信息
    /*
     * 楼盘维度的报备单明细
     *
     * By TerryQi
     *
     * 2018-02-19
     */
    public function stmt(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
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
        $pay_zhongjie_status = null;
        if (array_key_exists('pay_zhongjie_status', $data)) {
            $pay_zhongjie_status = $data['pay_zhongjie_status'];
        }
        //trade_no
        $trade_no = null;
        if (array_key_exists('trade_no', $data)) {
            $trade_no = $data['trade_no'];
        }
        //楼盘信息
        $house = HouseManager::getById($data['id']);
        $datas = BaobeiManager::getListForHouseByStatusPaginate($data['id'], $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no);
        foreach ($datas as $data) {
            $data = BaobeiManager::getInfoByLevel($data, "0");
        }

        //获取统计信息
        $stmt = new Collection([
            'all_nums' => BaobeiManager::getListForHouseByStatus($house->id, null, null, null, null, null, null)->count(),
            'baobei_status0' => BaobeiManager::getListForHouseByStatus($house->id, '0', null, null, null, null, null)->count(),
            'baobei_status1' => BaobeiManager::getListForHouseByStatus($house->id, '1', null, null, null, null, null)->count(),
            'baobei_status2' => BaobeiManager::getListForHouseByStatus($house->id, '2', null, null, null, null, null)->count(),
            'baobei_status3' => BaobeiManager::getListForHouseByStatus($house->id, '3', null, null, null, null, null)->count(),
            'baobei_status4' => BaobeiManager::getListForHouseByStatus($house->id, '4', null, null, null, null, null)->count(),
            'can_jiesuan_status1' => BaobeiManager::getListForHouseByStatus($house->id, null, '1', null, null, null, null)->count(),
            'pay_zhongjie_status1' => BaobeiManager::getListForHouseByStatus($house->id, null, null, '1', null, null, null)->count(),
        ]);
//        dd($stmt);
        return view('admin.house.stmt', ['admin' => $admin, 'house' => $house, 'stmt' => $stmt, 'datas' => $datas]);
    }


    //根据名称进行楼盘搜索
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $search_word = "";
//        dd($data['search_status']);
        //如果不存在search_status，代表搜索全部
        if (!array_key_exists('search_word', $data) || Utils::isObjNull($data['search_word'])) {
            $search_word = "";
        } else {
            $search_word = $data['search_word'];
        }
        $houses = HouseManager::searchByName($search_word);
        foreach ($houses as $house) {
            $house = HouseManager::getHouseInfoByLevel($house, "0");
        }
        $upload_token = QNManager::uploadToken();
        $houseTypes = HouseTypeManager::getList(); //获取房源类型
        $houseLabels = HouselabelManager::getList();        //获取房源标签
        return view('admin.house.index', ['admin' => $admin, 'datas' => $houses, 'upload_token' => $upload_token
            , 'houseTypes' => $houseTypes, 'houseLabels' => $houseLabels]);
    }


    //删除楼盘
    public function del(Request $request, $id)
    {
        //广告位id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $ad = House::find($id);
        $ad->delete();
        return redirect('/admin/house/index');
    }


    //新建或编辑楼盘-get
    public function edit(Request $request)
    {
        $data = $request->all();
        $house = new House();
        if (array_key_exists('id', $data)) {
            $house = HouseManager::getById($data['id']);
        }
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.house.edit', ['admin' => $admin, 'data' => $house, 'upload_token' => $upload_token]);
    }


    //新建或编辑楼盘->post
    public function editPost(Request $request)
    {

        $data = $request->all();
//        dd($data);
        $house = new House();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $house = House::find($data['id']);
        }
        if (!Utils::isObjNull($data['type_ids']) && is_array($data['type_ids'])) {
            $data['type_ids'] = implode(',', $data['type_ids']);
        }
        if (!Utils::isObjNull($data['label_ids']) && is_array($data['label_ids'])) {
            $data['label_ids'] = implode(',', $data['label_ids']);
        }
//        dd($data);
        $house = HouseManager::setHouse($house, $data);
        $house->save();
        return redirect('/admin/house/index');
    }


    /*
     * 根据id获取房源信息
     *
     * By TerryQi
     *
     * 2018-01-27
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

        $admin = HouseManager::getById($data['id']);
        return ApiResponse::makeResponse(true, $admin, ApiResponse::SUCCESS_CODE);

    }

    public function detail(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'house_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $admin = HouseManager::detail($data['house_id']);
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
        $house = HouseManager::getHouseById($data['house_id']);
        $upload_token = QNManager::uploadToken();
        return view('admin.house.getHouseById', ['admin' => $admin, 'datas' => $house, 'upload_token' => $upload_token]);
        //$house = HouseManager::getById($data['id']);
        return ApiResponse::makeResponse(true, $house, ApiResponse::SUCCESS_CODE);


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
        $house = House::where('id', '=', $id)->first();
        $house->status = $opt;
        $house->save();
        return redirect('/admin/house/index');
    }


    /*
    * 获取近日的数据
    *
    * By Yinyue
    *
    */
    public function getRecentDatas(Request $request)
    {
        $data = $request->all();
        $day_num = 15;
        if (!array_key_exists('day_num', $data) || Utils::isObjNull($data['day_num'])) {
            $day_num = 15;
        } else {
            $day_num = $data['day_num'];
        }
        $result = HouseManager::getRecentDatas($day_num);

        return ApiResponse::makeResponse(true, $result, ApiResponse::MISSING_PARAM);
    }


}