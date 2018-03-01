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
use App\Components\TWManager;
use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\TWStepManager;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\TWStep;
use App\Models\Doctor;
use App\Models\Article;
use App\Models\TWInfo;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class TWController
{

    //首页
    public function index(Request $request)
    {

        $admin = $request->session()->get('admin');
        $ads = TWManager::getList();
        foreach ($ads as $ad) {
            $ad->admin = AdminManager::getAdminInfoById($ad->admin_id);
            $ad->created_at_str = DateTool::formateData($ad->created_at, 1);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.tw.index', ['admin' => $admin, 'datas' => $ads, 'upload_token' => $upload_token]);
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
        $ad = TWInfo::where('id', '=', $id)->first();
        $ad->status = $opt;
        $ad->save();
        return redirect('/admin/tw/index');
    }


    //编辑图文,返回页面
    public function editTW(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $tw = new TWInfo();
        if (array_key_exists('id', $data)) {
            $tw = TWManager::getById($data['id']);
            //步骤信息
            $tw->steps = [];

           // $tw = TWStepManager::getStepById($data['f_id']);
            $tw = TWManager::getByType($tw->type);
        }

        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.tw.editTW', ['admin' => $admin, 'data' => $tw, 'upload_token' => $upload_token, ]);
    }
    /*
        * 编辑详细信息
        *
        * By TerryQi
        *
        * 2017-12-31
        *
        */
    public function editTWPost(Request $request)
    {
        //获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
        $data = $request->all();
//        dd($data);
        //新建/编辑信息
        $tw = new TWInfo();
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $tw = TWManager::getById($data['id']);
        }
        $tw = TWManager::setInfo($tw, $data);
        $tw->save();
        //获取数据库中原有的信息
        $ori_steps = TWStepManager::getStepsByFidAndFtable($tw->id, 't_tw_info');
        $new_steps = $data['steps'];
        //删除步骤
        foreach ($ori_steps as $ori_step) {
            if (!Utils::isIdInArray($ori_step->id, $new_steps)) {
                $ori_step->delete();
            }
        }
        //新建/编辑步骤
        foreach ($new_steps as $new_step) {
            $new_step['f_id'] = $tw->id;
            $new_step['f_table'] = "t_tw_info";
           // $new_step['f_table'] = "t_ad_info";
            $twStep = new TWStep();
            if (array_key_exists('id', $new_step) && !Utils::isObjNull($new_step['id'])) {
                $twStep = TWStepManager::getById($new_step['id']);
            }
            $twStep = TWStepManager::setInfo($twStep, $new_step);
            $twStep->save();
        }
        //重新获取合作细则信息并返回
        $tw = TWManager::getByType($tw->type);
        return ApiResponse::makeResponse(true, $tw, ApiResponse::SUCCESS_CODE);
    }

}