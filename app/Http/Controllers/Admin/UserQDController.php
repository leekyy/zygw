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
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserQDManager;
use App\Components\Utils;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class UserQDController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $userQDs = UserQDManager::getAllUserQDsPaginate();
//        dd($userQDs);
        foreach ($userQDs as $userQD) {
            $userQD = UserQDManager::getUserQDInfoByLevel($userQD, 0);
        }
        return view('admin.userQD.index', ['admin' => $admin, 'datas' => $userQDs]);
    }

    /*
     * 根据手机号码搜索用户签到信息
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'search_phonenum' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请输入查询手机号']);
        }
        if (!Utils::isPhonenum($data['search_phonenum'])) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，检索手机号码格式不正确']);
        }
        $userQDs = [];
        $user = UserManager::getUserInfoByTel($data['search_phonenum']);
        if ($user) {
            $userQDs = UserQDManager::getQDListByUserIdPaginate($user->id);
        }
        foreach ($userQDs as $userQD) {
            $userQD = UserQDManager::getUserQDInfoByLevel($userQD, 0);
        }
        return view('admin.userQD.index', ['admin' => $admin, 'datas' => $userQDs]);
    }

    /*
     * 签到综合统计
     *
     * By TerryQi
     *
     */
    public function stmt(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $stmt = collect();
        $stmt->zqdrs = UserQDManager::getAllQDRenShuNum();
        $stmt->zqdrcs = UserQDManager::getAllQDRenCiShuNum();
        $stmt->zpsjfs = UserQDManager::getAllPaiSongJiFenNum();
//        dd($stmt);
        return view('admin.userQD.stmt', ['admin' => $admin, 'data' => $stmt]);
    }

    /*
     * 获取近日的数据
     *
     * By TerryQi
     *
     */
    public function getRecentDatas(Request $request)
    {
        $data = $request->all();
        $day_num = 15;
        if (!array_key_exists('day_num', $data) || Utils::isObjNull($data['day_num'])) {
            $day_num = 15;
        } else {
            $day_num = $data['day_num'];
        }
        $result = UserQDManager::getRecentDatas($day_num);

        return ApiResponse::makeResponse(true, $result, ApiResponse::MISSING_PARAM);
    }
}