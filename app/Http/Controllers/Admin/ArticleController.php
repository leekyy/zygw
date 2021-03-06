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
use App\Components\ArticleManager;
use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\ArticleTWStep;
use App\Models\Doctor;
use App\Models\HeZuo;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class ArticleController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $ads = ArticleManager::getAllADs();
        foreach ($ads as $ad) {
            $ad->admin = AdminManager::getAdminInfoById($ad->admin_id);
            $ad->created_at_str = DateTool::formateData($ad->created_at, 1);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.article.index', ['admin' => $admin, 'datas' => $ads, 'upload_token' => $upload_token]);
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
        $ad = HeZuo::where('id', '=', $id)->first();
        $ad->status = $opt;
        $ad->save();
        return redirect('/admin/article/index');
    }

    //删除广告位
    public function del(Request $request, $id)
    {
        //广告位id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $ad = ArticleTWStep::find($id);
        $ad->delete();
        return redirect('/admin/article/index');
    }

    //新建或编辑广告位-get
    public function edit(Request $request)
    {
        $data = $request->all();
        $ad = new ArticleTWStep();
        if (array_key_exists('id', $data)) {
            $ad = ArticleTWStep::find($data['id']);
        }
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.article.edit', ['admin' => $admin, 'data' => $ad, 'upload_token' => $upload_token]);
    }

    //新建或编辑广告位->post
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $ad = new ArticleTWStep();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $ad = ArticleTWStep::find($data['id']);
        }
        $ad = ArticleManager::setAD($ad, $data);
        $ad->save();
        return redirect('/admin/article/index');
    }

}