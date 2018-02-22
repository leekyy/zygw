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
use App\Components\HouseManager;
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


class UserACFZRController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $users = UserManager::getListByRoleAndSearchWordPaginate('', ['1']);
        foreach ($users as $user) {
            $user->created_at_str = DateTool::formateData($user->created_at, 1);
            $user->userUps = UserUpManager::getUserUpHousesByUserId($user->id);
            foreach ($user->userUps as $userUp) {
                $userUp = UserUpManager::getUserUpInfoByLevel($userUp, "0");
            }
        }
        return view('admin.acfzr.index', ['admin' => $admin, 'datas' => $users]);
    }

    //统计页面
    public function stmt(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
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
        $datas = BaobeiManager::getListForACByStatusPaginate($data['id'], $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no);
        foreach ($datas as $data) {
            $data = BaobeiManager::getInfoByLevel($data, "0");
        }
        //获取统计信息
        $stmt = new Collection([
            'all_nums' => BaobeiManager::getListForACByStatus($user->id, null, null, null, null, null, null)->count(),
            'baobei_status0' => BaobeiManager::getListForACByStatus($user->id, '0', null, null, null, null, null)->count(),
            'baobei_status1' => BaobeiManager::getListForACByStatus($user->id, '1', null, null, null, null, null)->count(),
            'baobei_status2' => BaobeiManager::getListForACByStatus($user->id, '2', null, null, null, null, null)->count(),
            'baobei_status3' => BaobeiManager::getListForACByStatus($user->id, '3', null, null, null, null, null)->count(),
            'baobei_status4' => BaobeiManager::getListForACByStatus($user->id, '4', null, null, null, null, null)->count(),
            'can_jiesuan_status1' => BaobeiManager::getListForACByStatus($user->id, null, '1', null, null, null, null)->count(),
            'pay_zhongjie_status1' => BaobeiManager::getListForACByStatus($user->id, null, null, '1', null, null, null)->count(),
        ]);
//        dd($stmt);
        return view('admin.acfzr.stmt', ['admin' => $admin, 'user' => $user, 'stmt' => $stmt, 'datas' => $datas]);
    }

    //搜索
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $users = UserManager::getListByRoleAndSearchWordPaginate($data['search_word'], ['1']);
        foreach ($users as $user) {
            $user->created_at_str = DateTool::formateData($user->created_at, 1);
            $user->userUps = UserUpManager::getUserUpHousesByUserId($user->id);
            foreach ($user->userUps as $userUp) {
                $userUp = UserUpManager::getUserUpInfoByLevel($userUp, "0");
            }
        }
        return view('admin.acfzr.index', ['admin' => $admin, 'datas' => $users]);
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
        $user = UserManager::getByIdWithToken($id);
        $user->status = $status;
        $user->save();
        return redirect('/admin/acfzr/index');
    }

}