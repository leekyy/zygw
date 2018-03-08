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
//use App\Components\HouseTypeManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Components\HouseManager;
use App\Components\HouseImageManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\House;
use App\Models\HouseImage;
use App\Models\Huxing;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class HouseImageController
{
    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $houseTypes = HouseTypeManager::getList();
        foreach ($houseTypes as $houseType) {
            $houseType->admin = AdminManager::getAdminInfoById($houseType->admin_id);
            $houseType->created_at_str = DateTool::formateData($houseType->created_at, 1);
        }
        return view('admin.houseType.index', ['admin' => $admin, 'datas' => $houseTypes]);
    }


    //新建或编辑楼盘标签->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $houseType = new HouseImage();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $houseType = HouseType::find($data['id']);
        }
//        dd($data);
        $houseType = HouseTypeManager::setHouseType($houseType, $data);
        $houseType->save();

        return redirect('/admin/houseType/index');
    }
}