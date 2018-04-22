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
use App\Components\HouseDetailManager;
use App\Components\HouselabelManager;
use App\Components\HouseTypeManager;
use App\Components\HuxingManager;
use App\Components\HuxingStyleManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Components\HouseManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\House;
use App\Models\HouseDetail;
use App\Models\HouseType;
use App\Models\Huxing;
use App\Models\HuxingStyle;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;


class HuxingStyleController
{

    //首页
    public function index(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'huxing_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //获得产品及楼盘信息
        $huxing = HuxingManager::getById($data['huxing_id']);
        $huxing = HuxingManager::getInfoByLevel($huxing, '');
        //获得该产品下的户型信息
        $con_arr = array(
            'huxing_id' => $huxing->id
        );
        $huxingStyles = HuxingStyleManager::getListByCon($con_arr, false);
//        dd($huxingStyles);

        foreach ($huxingStyles as $huxingStyle) {
            $huxingStyle = HuxingStyleManager::getInfoByLevel($huxingStyle, '');
        }

        $upload_token = QNManager::uploadToken();

        return view('admin.huxingStyle.index', ['admin' => $admin, 'datas' => $huxingStyles, 'upload_token' => $upload_token
            , 'huxing' => $huxing]);
    }


    //删除户型样式
    public function del(Request $request, $id)
    {
        //广告位id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $huxingStyle = HuxingStyleManager::getById($id);
        $huxing_id = $huxingStyle->huxing_id;
        $huxingStyle->delete();
        return redirect('/admin/huxingStyle/index?huxing_id=' . $huxing_id);
    }

    /*
     * 新建或编辑户型样式
     *
     * By TerryQi
     *
     * 2018-4-22
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
        $huxingStyle = HuxingStyleManager::getById($data['id']);
        return ApiResponse::makeResponse(true, $huxingStyle, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 编辑户型详情
     *
     * By TerryQi
     *
     * 2018-04-22
     */
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $huxingStyle = new HuxingStyle();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $huxingStyle = HuxingStyleManager::getById($data['id']);
        }
//        dd($data);
        $huxingStyle = HuxingStyleManager::setInfo($huxingStyle, $data);
        $huxingStyle->save();
        return redirect('/admin/huxingStyle/index?huxing_id=' . $huxingStyle->huxing_id);
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
        $huxingStyle = HuxingStyleManager::getById($id);
        $huxingStyle->status = $opt;
        $huxingStyle->save();
        return redirect('/admin/huxingStyle/index?huxing_id=' . $huxingStyle->huxing_id);
    }

}