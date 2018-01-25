<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\GoodsManager;
use App\Components\GoodsExchangeManager;
use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Libs\CommonUtils;
use App\Models\Goods;
use App\Models\GoodsExchange;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class GoodsExchangeController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $userUps = GoodsExchangeManager::getListPaginate();
//        dd($userUps);
        foreach ($userUps as $userUp) {
            $userUp = GoodsExchangeManager::getUserUpInfoByLevel($userUp, 0);
        }
        return view('admin.goodsexchange.index', ['admin' => $admin, 'datas' => $userUps]);
    }


    //设置状态
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'status' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //opt必须为0或者1
        $status = $data['status'];
        if (!($status == '0' || $status == '1')) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数,opt必须为0或者1，现值为' . $opt]);
        }
        $goodsexchange = GoodsExchangeManager::getById($id);
        $goodsexchange->status = $status;
        $goodsexchange->save();
        return redirect('/admin/goodsexchange/index');
    }
    /*
    * 订单综合统计
    *
    * By Yinyue
    *
    */

    public function stmt(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $stmt = collect();
        $stmt->zqdrs = GoodsExchangeManager::getAllQDRenShuNum();
        $stmt->zqdrcs = GoodsExchangeManager::getAllQDRenCiShuNum();
        $stmt->zpsjfs = GoodsExchangeManager::getAllPaiSongJiFenNum();
//        dd($stmt);
        return view('admin.goodsexchange.stmt', ['admin' => $admin, 'data' => $stmt]);
    }

    //删除订单
    public function del(Request $request, $id)
    {
        //广告位id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $goodsexchange = GoodsExchange::find($id);
        $goodsexchange->delete();
        return redirect('/admin/goodsexchange/index');
    }

    /*
      * 根据id获取订单信息
      *
      * By Yinyue
      *
      * 2018-01-24
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
        $goodsexchange = GoodsExchangeManager::getById($data['id']);
        return ApiResponse::makeResponse(true, $goodsexchange, ApiResponse::SUCCESS_CODE);

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
        $result = GoodsExchangeManager::getRecentDatas($day_num);

        return ApiResponse::makeResponse(true, $result, ApiResponse::MISSING_PARAM);
    }

    //新建或编辑订单-get
    public function edit(Request $request)
    {
        $data = $request->all();
        $house = new GoodsExchange();
        if (array_key_exists('id', $data)) {
            $house = GoodsExchange::find($data['id']);
        }
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.goodsexchange.edit', ['admin' => $admin, 'data' => $house, 'upload_token' => $upload_token]);
    }

    //新建或编辑楼盘->post
    public function editPost(Request $request)
    {

        $data = $request->all();
//        dd($data);
        $goodsExchange = new GoodsExchange();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $goodsExchange = GoodsExchange::find($data['id']);
        }
        $goodsExchange = GoodsExchangeManager::setGoodsExchange($goodsExchange, $data);
        $goodsExchange->save();
        return redirect('/admin/goodsexchange/index');
    }




}