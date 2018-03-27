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
use App\Components\HouselabelManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Components\HouseManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\House;
use App\Models\HouseLabel;
use App\Models\Huxing;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class HouseLabelController
{
    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $houseLabels = HouselabelManager::getList();
        foreach ($houseLabels as $houseLabel) {
            $houseLabel->admin = AdminManager::getAdminInfoById($houseLabel->admin_id);
            $houseLabel->created_at_str = DateTool::formateData($houseLabel->created_at, 1);
        }
        return view('admin.houseLabel.index', ['admin' => $admin, 'datas' => $houseLabels]);
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
        $houseLabel = HouselabelManager::getById($data['id']);
        return ApiResponse::makeResponse(true, $houseLabel, ApiResponse::SUCCESS_CODE);

    }



    //新建或编辑楼盘标签->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $houseLabel = new HouseLabel();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $houseLabel = HouseLabel::find($data['id']);
        }
//        dd($data);
        $houseLabel = HouselabelManager::setHouseLabel($houseLabel, $data);
        $houseLabel->save();

        return redirect('/admin/houseLabel/index');
    }
}