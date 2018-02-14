<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/10/3
 * Time: 0:38
 */

namespace App\Http\Controllers\Admin;

use App\Components\AdminManager;
use App\Libs\CommonUtils;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use App\Models\Admin;

class LoginController
{
    //GET方式-转移登录界面
    public function login()
    {
        return view('admin.login.loginPage', ['msg' => '']);
    }

    //POST-实现登录逻辑
    public function loginPost(Request $request)
    {
        //参数校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'phonenum' => 'required',
            'password' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return view('admin.login.loginPage', '请输入手机号和密码');
        }
        $phonenum = $request->phonenum;
        $password = $request->password;
        $admin = AdminManager::login($phonenum, $password);
        //登录失败
        if ($admin == null) {
            return view('admin.login.loginPage', ['msg' => '手机号或密码错误']);
        }
        $request->session()->put('admin', $admin);//写入session
        return redirect('/admin/dashboard/index');//跳转至后台首页
    }

    //注销登录
    public function loginout(Request $request)
    {
        //清空session
        $request->session()->remove('admin');
        return redirect('/admin/login');
    }

}