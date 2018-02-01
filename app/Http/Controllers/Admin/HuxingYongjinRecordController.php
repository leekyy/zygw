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
use App\Components\HouseTypeManager;
use App\Components\HuxingYongjinRecordManager;
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
use App\Models\HuxingYongjingRecord;
use App\Models\HuxingYongjinRecord;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class HuxingYongjinRecordController
{
    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
//        dd($data);
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'huxing_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $huxingYongjinRecords = HuxingYongjinRecordManager::getListByHuxingIdPaginate($data['huxing_id']);
        foreach ($huxingYongjinRecords as $huxingYongjinRecord) {
            $huxingYongjinRecord = HuxingYongjinRecordManager::getInfoByLevel($huxingYongjinRecord, '0');
        }
        return view('admin.houseHuxingYongjinRecord.index', ['admin' => $admin, 'datas' => $huxingYongjinRecords]);
    }

}