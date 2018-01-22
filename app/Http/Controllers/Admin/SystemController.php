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
use App\Components\SystemManager;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\Doctor;
use App\Models\System;
use App\Models\SystemRecord;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class SystemController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $systemInfo = SystemManager::getSystemInfo();
        $systemRecords = SystemManager::getAllSystemRecords();
        foreach ($systemRecords as $systemRecord) {
            $systemRecord = SystemManager::getSystemRecordByLevel($systemRecord, 0);
        }
        return view('admin.system.index', ['admin' => $admin, 'data' => $systemInfo, 'systemRecords' => $systemRecords]);
    }


    //新建或编辑系统配置-get
    public function edit(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //获取系统配置
        $system = SystemManager::getSystemInfo();
        return ApiResponse::makeResponse(true, $system, ApiResponse::SUCCESS_CODE);
    }

    //新建或编辑系统配置->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'admin_id' => 'required',
            'qd_jifen' => 'required',
            'tj_jifen' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //保存设置的系统配置
        $system = SystemManager::getSystemInfo();
        $system = SystemManager::setSystemInfo($system, $data);
        $system->save();
        //设置记录
        $systemRecord = new SystemRecord();
        $systemRecord->admin_id = $data['admin_id'];
        $systemRecord->desc = "系统配置 签到积分规则变更为：" . $data['qd_jifen'] . " 推荐积分规则变更为：" . $data['tj_jifen'];
        $systemRecord->save();
        return redirect('/admin/system/index');
    }

}