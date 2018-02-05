<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\WhiteBookManager;
use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\XJManager;
use App\Components\Utils;
use App\Libs\CommonUtils;
use App\Models\WhiteBook;
use App\Models\Doctor;
use App\Models\WhiteBookTWStep;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class WhiteBookController
{
    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $xjs = WhiteBookManager::getIndexXJs();
        foreach ($xjs as $xj) {
            $xj->created_at_str = DateTool::formateData($xj->created_at, 1);
            $xj = WhiteBookManager::getXJInfoByLevel($xj, 0);
        }
        return view('admin.whitebook.index', ['admin' => $admin, 'datas' => $xjs]);
    }
    //编辑行业白皮书,返回页面
    public function editWhiteBook(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //types
        //$hposs = HposManager::getHPosList();
        $xj = new WhiteBook();
        if (array_key_exists('id', $data)) {
            $xj = WhiteBookManager::getXJById($data['id']);
            //步骤信息
            $xj->steps = [];
            $xj = WhiteBookManager::getXJInfoByLevel($xj, 3);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.whitebook.editWhiteBook', ['admin' => $admin, 'data' => $xj, 'upload_token' => $upload_token, ]);
    }
    /*
     * 编辑行业白皮书详细信息
     *
     * By TerryQi
     *
     * 2017-12-31
     *
     */
    public function editWhiteBookPost(Request $request)
    {
        //获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
        $data = $request->all();
//        dd($data);
        //新建/编辑行业白皮书信息
        $xj = new WhiteBook();
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $xj = WhiteBookManager::getXJById($data['id']);
        }
        $xj = WhiteBookManager::setWhiteBook($xj, $data);
        $xj->save();
        //获取数据库中原有的信息
        $ori_steps = WhiteBookManager::getStepsByFidAndFtable($xj->id, 'xj');
        $new_steps = $data['steps'];
        //删除步骤
        foreach ($ori_steps as $ori_step) {
            if (!Utils::isIdInArray($ori_step->id, $new_steps)) {
                $ori_step->delete();
            }
        }
        //新建/编辑步骤
        foreach ($new_steps as $new_step) {
            $new_step['f_id'] = $xj->id;
            $new_step['f_table'] = "xj";
            $twStep = new WhiteBookTWStep();
            if (array_key_exists('id', $new_step) && !Utils::isObjNull($new_step['id'])) {
                $twStep = WhiteBookManager::getStepById($new_step['id']);
            }
            $twStep = WhiteBookManager::setWhiteBookStep($twStep, $new_step);
            $twStep->save();
        }
        //重新获取行业白皮书信息并返回
        $xj = WhiteBookManager::getXJInfoByLevel($xj, 3);
        return ApiResponse::makeResponse(true, $xj, ApiResponse::SUCCESS_CODE);
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
        $xjTypes = WhiteBookManager::getXJTypes();
        foreach ($xjTypes as $xjType) {
            $xjType->created_at_str = DateTool::formateData($xjType->created_at, 1);
        }
        return view('admin.xjType.index', ['admin' => $admin, 'datas' => $xjTypes]);
    }
    /*
     * 根据id获取行业白皮书详情
     *
     * By TerryQi
     *
     * 2017-120=-07
     *
     */
    public function setStep(Request $request, $id)
    {
        $admin = $request->session()->get('admin');
        $xj = WhiteBookManager::getXJById($id);
        $xj->steps = [];
        $xj->created_at_str = DateTool::formateData($xj->created_at, 1);
        $xj = WhiteBookManager::getXJInfoByLevel($xj, 3);
        foreach ($xj->steps as $step) {
            $step->created_at_str = DateTool::formateData($step->created_at, 1);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.whitebook.editStep', ['admin' => $admin, 'data' => $xj, 'upload_token' => $upload_token]);
    }
    /*
     * 添加行业白皮书步骤信息
     *
     * By TerryQi
     *
     * 2017-12-07
     *
     */
    public function setStepPost(Request $request)
    {
        $data = $request->all();
        $tw_step = new WhiteBookTWStep();
        $tw_step = WhiteBookManager::setWhiteBookStep($tw_step, $data);
        $tw_step->f_table = "xj";
        $tw_step->save();
        return redirect('/admin/whitebook/setStep/' . $tw_step->f_id);
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
        $xj = WhiteBook::where('id', '=', $id)->first();
        $xj->status = $opt;
        $xj->save();
        return redirect('/admin/whitebook/index');
    }
    //删除行业白皮书
    public function del(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数宣教id$id']);
        }
        $xj = WhiteBookManager::getXJById($id);
        $xj->delete();
        return redirect('/admin/whitebook/index');
    }
    //删除行业白皮书步骤
    public function delStep(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数宣教id$id']);
        }
        $tw_step = WhiteBookManager::getStepById($id);
        $xj_id = $tw_step->f_id;
        $tw_step->delete();
        return redirect('/admin/whitebook/setStep/' . $xj_id);
    }
    //新建或编辑行业白皮书-get
    public function edit(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $xj = new WhiteBook();
        //types
        //$xj_types = XJType::all();
        if (array_key_exists('id', $data)) {
            $xj = WhiteBookManager::getXJById($data['id']);
            //步骤信息
            $xj->steps = [];
            $xj->created_at_str = DateTool::formateData($xj->created_at, 1);
            $xj = WhiteBookManager::getXJInfoByLevel($xj, 3);
            foreach ($xj->steps as $step) {
                $step->created_at_str = DateTool::formateData($step->created_at, 1);
            }
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.whitebook.edit', ['admin' => $admin, 'data' => $xj, 'upload_token' => $upload_token, 'xj_types' => $xj_types]);
    }

    //新建或编辑行业白皮书->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $xj = new WhiteBook();
        //存在id是保存
        if (array_key_exists('id', $data)) {
            $xj = WhiteBookManager::getXJById($data['id']);
        }
        $xj = WhiteBookManager::setWhiteBook($xj, $data);
        $xj->save();
        return redirect('/admin/whitebook/edit' . '?id=' . $xj->id);
    }

}
