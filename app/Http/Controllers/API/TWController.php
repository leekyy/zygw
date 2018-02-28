<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\API;

use App\Components\TWManager;
use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\TWStepManager;
use App\Components\XJManager;
use App\Components\Utils;
use App\Libs\CommonUtils;
use App\Models\Article;
use App\Models\Doctor;
use App\Models\TWStep;
use App\Models\TWInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class TWController
{
    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $xjs = TWManager::getList();
        foreach ($xjs as $tw) {
            $tw->created_at_str = DateTool::formateData($tw->created_at, 1);
            $tw = TWManager::getByType($tw->type);
        }
        return view('admin.hezuo.index', ['admin' => $admin, 'datas' => $xjs]);
    }

    /*
    * 根据id获取合作细则详情
    *
    * By TerryQi
    *
    * 2017-12-08
    *
    */
    public function getInfoById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $tw = TWManager::getById($data['id']);
        if (!$tw) {
            return ApiResponse::makeResponse(false, '未找到合作细则信息', ApiResponse::INNER_ERROR);
        }
        $tw = TWManager::getByType($tw->type);
        return ApiResponse::makeResponse(true, $tw, ApiResponse::SUCCESS_CODE);
    }






    //编辑合作细则,返回页面
    public function editTW(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //types
        //$hposs = HposManager::getHPosList();
        $tw = new TWInfo();
        if (array_key_exists('id', $data)) {
            $tw = TWManager::getById($data['id']);
            //步骤信息
            $tw->steps = [];
            $tw = TWManager::getByType($tw->type);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.hezuo.editHeZuo', ['admin' => $admin, 'data' => $tw, 'upload_token' => $upload_token, ]);
    }
    /*
     * 编辑合作细则详细信息
     *
     * By TerryQi
     *
     * 2017-12-31
     *
     */
    public function editHeZuoPost(Request $request)
    {
        //获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
        $data = $request->all();
//        dd($data);
        //新建/编辑宣教信息
        $tw = new TWInfo();
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $tw = TWManager::getById($data['id']);
        }
        $tw = TWManager::setInfo($tw, $data);
        $tw->save();
        //获取数据库中原有的信息
        $ori_steps = TWStepManager::getStepsByFidAndFtable($tw->id, 'tw');
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
            $new_step['f_table'] = "tw";
            $twStep = new TWStep();
            if (array_key_exists('id', $new_step) && !Utils::isObjNull($new_step['id'])) {
                $twStep = TWStepManager::getStepById($new_step['id']);
            }
            $twStep = TWStepManager::setInfo($twStep, $new_step);
            $twStep->save();
        }
        //重新获取合作细则信息并返回
        $tw = TWManager::getByType($tw->type);
        return ApiResponse::makeResponse(true, $tw, ApiResponse::SUCCESS_CODE);
    }
    /*
     * 分类首页
     *
     * By TerryQi
     *
     * 2017-12-11
     *
     */
    public function indexType(Request $request)
    {
        $admin = $request->session()->get('admin');
        $xjTypes = TWManager::getByType($xjTypes->type);
        foreach ($xjTypes as $xjType) {
            $xjType->created_at_str = DateTool::formateData($xjType->created_at, 1);
        }
        return view('admin.xjType.index', ['admin' => $admin, 'datas' => $xjTypes]);
    }
    /*
     * 根据id获取合作细则详情
     *
     * By TerryQi
     *
     * 2017-120=-07
     *
     */
    public function setStep(Request $request, $id)
    {
        $admin = $request->session()->get('admin');
        $xj = TWManager::getXJById($id);
        $xj->steps = [];
        $xj->created_at_str = DateTool::formateData($xj->created_at, 1);
        $xj = TWManager::getXJInfoByLevel($xj, 3);
        foreach ($xj->steps as $step) {
            $step->created_at_str = DateTool::formateData($step->created_at, 1);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.hezuo.editStep', ['admin' => $admin, 'data' => $xj, 'upload_token' => $upload_token]);
    }
    /*
     * 添加宣教步骤信息
     *
     * By TerryQi
     *
     * 2017-12-07
     *
     */
    public function setStepPost(Request $request)
    {
        $data = $request->all();
        $tw_step = new ArticleTWStep();
        $tw_step = TWManager::setHeZuoStep($tw_step, $data);
        $tw_step->f_table = "xj";
        $tw_step->save();
        return redirect('/admin/hezuo/setStep/' . $tw_step->f_id);
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
        $xj = Article::where('id', '=', $id)->first();
        $xj->status = $opt;
        $xj->save();
        return redirect('/admin/hezuo/index');
    }
    //删除宣教
    public function del(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数宣教id$id']);
        }
        $xj = TWManager::getXJById($id);
        $xj->delete();
        return redirect('/admin/hezuo/index');
    }
    //删除合作细则步骤
    public function delStep(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数宣教id$id']);
        }
        $tw_step = TWManager::getStepById($id);
        $xj_id = $tw_step->f_id;
        $tw_step->delete();
        return redirect('/admin/hezuo/setStep/' . $xj_id);
    }
    //新建或编辑宣教-get
    public function edit(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $xj = new Article();
        //types
        //$xj_types = XJType::all();
        if (array_key_exists('id', $data)) {
            $xj = TWManager::getXJById($data['id']);
//            foreach ($xj_types as $xj_type) {
//                if (in_array($xj_type->id, explode(",", $xj->type))) {
//                    $xj_type->checked = true;
//                }
//            }
            //步骤信息
            $xj->steps = [];
            $xj->created_at_str = DateTool::formateData($xj->created_at, 1);
            $xj = TWManager::getXJInfoByLevel($xj, 3);
            foreach ($xj->steps as $step) {
                $step->created_at_str = DateTool::formateData($step->created_at, 1);
            }
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.hezuo.edit', ['admin' => $admin, 'data' => $xj, 'upload_token' => $upload_token, 'xj_types' => $xj_types]);
    }
//    //新建或编辑宣教类型-post
//    public function editType(Request $request)
//    {
//        $admin = $request->session()->get('admin');
//        $data = $request->all();
//        $xjType = new XJType();
//        if (array_key_exists('id', $data)) {
//            $xjType = XJManager::getXJTypeById($data['id']);
//        }
//        //步骤信息
//        return view('admin.xjType.edit', ['admin' => $admin, 'data' => $xjType]);
//    }
    //新建或编辑合作细则->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $xj = new Article();
        //存在id是保存
        if (array_key_exists('id', $data)) {
            $xj = TWManager::getXJById($data['id']);
        }
        $xj = TWManager::setXJ($xj, $data);
        $xj->save();
        return redirect('/admin/hezuo/edit' . '?id=' . $xj->id);
    }
//    //新建或编辑宣教类别->post
//    public function editTypePost(Request $request)
//    {
//        $data = $request->all();
//        $xjType = new XJType();
//        //存在id是保存
//        if (array_key_exists('id', $data) && $data['id'] != null) {
//            $xjType = XJManager::getXJTypeById($data['id']);
//        }
//        $xjType = XJManager::setXJType($xjType, $data);
//        $xjType->save();
//        return redirect('/admin/xjType/index');
//    }
}
