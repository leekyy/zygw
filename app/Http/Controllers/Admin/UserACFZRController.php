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
use App\Components\DateTool;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\AD;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class UserACFZRController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $users = UserManager::getListByRoleAndSearchWordPaginate('', '1');
        foreach ($users as $user) {
            $user->created_at_str = DateTool::formateData($user->created_at, 1);;
        }
        return view('admin.acfzr.index', ['admin' => $admin, 'datas' => $users]);
    }

    //搜索
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $users = UserManager::getListByRoleAndSearchWordPaginate($data['search_word'], '1');
        foreach ($users as $user) {
            $user->created_at_str = DateTool::formateData($user->created_at, 1);;
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
        $user = User::where('id', '=', $id)->first();
        $user->status = $status;
        $user->save();
        return redirect('/admin/acfzr/index');
    }

}