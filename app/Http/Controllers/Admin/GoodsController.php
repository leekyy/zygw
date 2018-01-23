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
use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Libs\CommonUtils;
use App\Models\Goods;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class GoodsController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $goodses = GoodsManager::getListByStatusPaginate(['0', '1']);
        foreach ($goodses as $goods) {
            $goods->created_at_str = DateTool::formateData($goods->created_at, 1);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.goods.index', ['admin' => $admin, 'datas' => $goodses, 'upload_token' => $upload_token]);
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
        $goods = GoodsManager::getById($id);
        $goods->status = $status;
        $goods->save();
        return redirect('/admin/goods/index');
    }

    //删除广告位
    public function del(Request $request, $id)
    {
        //广告位id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $goods = Goods::find($id);
        $goods->delete();
        return redirect('/admin/goods/index');
    }

    /*
      * 根据id获取管理员信息
      *
      * By TerryQi
      *
      * 2018-01-20
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
        $goods = GoodsManager::getById($data['id']);
        return ApiResponse::makeResponse(true, $goods, ApiResponse::SUCCESS_CODE);

    }

    //新建或编辑广告位->post
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $goods = new Goods();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $goods = Goods::find($data['id']);
        }
        $goods = GoodsManager::setGoods($goods, $data);
        $goods->save();
        return redirect('/admin/goods/index');
    }

}