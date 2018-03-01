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
use App\Components\ADStepManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\Doctor;
use App\Components\Utils;
use App\Models\TWStep;
use App\Http\Controllers\ApiResponse;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class ADController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $ads = ADManager::getAllADs();
        foreach ($ads as $ad) {
            $ad->admin = AdminManager::getAdminInfoById($ad->admin_id);
            $ad->created_at_str = DateTool::formateData($ad->created_at, 1);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.ad.index', ['admin' => $admin, 'datas' => $ads, 'upload_token' => $upload_token]);
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
        $ad = AD::where('id', '=', $id)->first();
        $ad->status = $opt;
        $ad->save();
        return redirect('/admin/ad/index');
    }

    //删除广告位
    public function del(Request $request, $id)
    {
        //广告位id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $ad = AD::find($id);
        $ad->delete();
        return redirect('/admin/ad/index');
    }

    //新建或编辑广告位-get
    public function edit(Request $request)
    {
        $data = $request->all();
        $ad = new AD();
        if (array_key_exists('id', $data)) {
            $ad = AD::find($data['id']);
        }
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.ad.edit', ['admin' => $admin, 'data' => $ad, 'upload_token' => $upload_token]);
    }

    //新建或编辑广告位->post
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $ad = new AD();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $ad = AD::find($data['id']);
        }
        $ad = ADManager::setInfo($ad, $data);
        $ad->save();
        return redirect('/admin/ad/index');
    }

    //编辑图文,返回页面
    public function editAD(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $tw = new AD();
        if (array_key_exists('id', $data)) {
            $tw = ADManager::getADById($data['id']);
            //步骤信息
            $tw->steps = [];
            $tw = ADManager::getByType($tw->type);
        }

        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.ad.editAD', ['admin' => $admin, 'data' => $tw, 'upload_token' => $upload_token, ]);
    }
    /*
        * 编辑详细信息
        *
        * By TerryQi
        *
        * 2017-12-31
        *
        */
    public function editADPost(Request $request)
    {
        //获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
        $data = $request->all();
//        dd($data);
        //新建/编辑信息
        $tw = new AD();
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $tw = ADManager::getADById($data['id']);
        }
        $tw = ADManager::setInfo($tw, $data);
        $tw->save();
        //获取数据库中原有的信息
        $ori_steps = ADStepManager::getStepsByFidAndFtable($tw->id, 't_ad_info');
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
            $new_step['f_table'] = "t_ad_info";
            $twStep = new TWStep();
            if (array_key_exists('id', $new_step) && !Utils::isObjNull($new_step['id'])) {
                $twStep = ADStepManager::getById($new_step['id']);
            }
            $twStep = ADStepManager::setInfo($twStep, $new_step);
            $twStep->save();
        }
        //重新获取合作细则信息并返回
        $tw = ADManager::getByType($tw->type);
        return ApiResponse::makeResponse(true, $tw, ApiResponse::SUCCESS_CODE);
    }

}