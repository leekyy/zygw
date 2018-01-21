<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\AD;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class AdminController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $admins = Admin::orderBy('id', 'asc')->get();
        foreach ($admins as $s_admin) {
            $s_admin->created_at_str = DateTool::formateData($s_admin->created_at, 1);;
        }
//        dd($admins);
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.admin.index', ['admin' => $admin, 'datas' => $admins, 'upload_token' => $upload_token]);
    }


    //删除管理员
    public function del(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数管理员id$id']);
        }
        $admin = Admin::find($id);
        //非根管理员
        if (!($admin->role == '0')) {
            $admin->delete();
        }
        return redirect('/admin/admin/index');
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
        $admin = AdminManager::getAdminInfoById($data['id']);
        return ApiResponse::makeResponse(true, $admin, ApiResponse::SUCCESS_CODE);

    }


    //新建或编辑管理员-get
    public function edit(Request $request)
    {
        $data = $request->all();
        $admin_b = new Admin();
        if (array_key_exists('id', $data)) {
            $admin_b = Admin::find($data['id']);
        }
        $admin = $request->session()->get('admin');
        //只有根管理员有修改权限
        if (!($admin->role == '0')) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，只有根级管理员有修改权限']);
        }

        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.admin.edit', ['admin' => $admin, 'data' => $admin_b, 'upload_token' => $upload_token]);
    }

    //新建或编辑管理员->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = new Admin();
        //存在id是保存
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $admin = AdminManager::getAdminInfoById($data['id']);
        }
        $admin = AdminManager::setAdmin($admin, $data);
        //如果不存在id代表新建，则默认设置密码
        if (!array_key_exists('id', $data)) {
            $admin->password = 'afdd0b4ad2ec172c586e2150770fbf9e';  //该password为Aa123456的码
        }
        $admin->save();
        return redirect('/admin/admin/index');
    }

}