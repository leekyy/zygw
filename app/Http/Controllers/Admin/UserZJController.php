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
use App\Components\BaobeiManager;
use App\Components\DateTool;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\AD;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;


class UserZJController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $users = UserManager::getListByRoleAndSearchWordPaginate('', ['0', '1']);
        foreach ($users as $user) {
            $user->created_at_str = DateTool::formateData($user->created_at, 1);
        }
        return view('admin.zhongjie.index', ['admin' => $admin, 'datas' => $users]);
    }

    //搜索
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $users = UserManager::getListByRoleAndSearchWordPaginate($data['search_word'], ['0', '1']);
        foreach ($users as $user) {
            $user->created_at_str = DateTool::formateData($user->created_at, 1);
        }
        return view('admin.zhongjie.index', ['admin' => $admin, 'datas' => $users]);
    }

    //支付佣金
    public function payYongjin(Request $request)
    {
        $data = $request->all();
//        dd($data);
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'baobei_id' => 'required',
            'admin_id' => 'required',
            'pay_zhongjie_attach' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Usermin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $baobei = BaobeiManager::getById($data['baobei_id']);
        $baobei->pay_admin_id = $data['admin_id'];
        $baobei->pay_zhongjie_time = DateTool::getCurrentTime();
        $baobei->pay_zhongjie_status = '1';
        $baobei->pay_zhongjie_attach = $data['pay_zhongjie_attach'];
        $baobei->save();
        return redirect('/admin/zhongjie/smst?id=' . $baobei->user_id);
    }

    //设置状态
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'status' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Usermin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //status必须为0或者1
        $status = $data['status'];
        if (!($status == '0' || $status == '1')) {
            return redirect()->action('\App\Http\Controllers\Usermin\IndexController@error', ['msg' => '合规校验失败，请检查参数,status必须为0或者1，现值为' . $status]);
        }
        $user = User::where('id', '=', $id)->first();
        $user->status = $status;
        $user->save();
        return redirect('/admin/zhongjie/index');
    }

    //统计页面
    public function smst(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }

        //报备状态条件
        $baobei_status = null;
        if (array_key_exists('baobei_status', $data)) {
            $baobei_status = $data['baobei_status'];
        }
        //是否可以结算条件
        $can_jiesuan_status = null;
        if (array_key_exists('can_jiesuan_status', $data)) {
            $can_jiesuan_status = $data['can_jiesuan_status'];
        }
        //是否已经结算条件
        $pay_zhongjie_status = null;
        if (array_key_exists('pay_zhongjie_status', $data)) {
            $pay_zhongjie_status = $data['pay_zhongjie_status'];
        }
        //trade_no
        $trade_no = null;
        if (array_key_exists('trade_no', $data)) {
            $trade_no = $data['trade_no'];
        }
        $user = UserManager::getById($data['id']);
        $datas = BaobeiManager::getListForZJByStatusPaginate($data['id'], $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no);
        foreach ($datas as $data) {
            $data = BaobeiManager::getInfoByLevel($data, "0");
        }

        //获取统计信息
        $stmt = new Collection([
            'all_nums' => BaobeiManager::getListForZJByStatus($user->id, null, null, null, null, null, null)->count(),
            'baobei_status0' => BaobeiManager::getListForZJByStatus($user->id, '0', null, null, null, null, null)->count(),
            'baobei_status1' => BaobeiManager::getListForZJByStatus($user->id, '1', null, null, null, null, null)->count(),
            'baobei_status2' => BaobeiManager::getListForZJByStatus($user->id, '2', null, null, null, null, null)->count(),
            'baobei_status3' => BaobeiManager::getListForZJByStatus($user->id, '3', null, null, null, null, null)->count(),
            'baobei_status4' => BaobeiManager::getListForZJByStatus($user->id, '4', null, null, null, null, null)->count(),
            'can_jiesuan_status1' => BaobeiManager::getListForZJByStatus($user->id, null, '1', null, null, null, null)->count(),
            'pay_zhongjie_status1' => BaobeiManager::getListForZJByStatus($user->id, null, null, '1', null, null, null)->count(),
        ]);
//        dd($stmt);
        return view('admin.zhongjie.smst', ['admin' => $admin, 'user' => $user, 'smst' => $stmt, 'datas' => $datas, 'upload_token' => $upload_token]);
    }

}