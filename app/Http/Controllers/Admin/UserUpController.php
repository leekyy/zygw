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
use App\Components\HouseManager;
use App\Components\QNManager;
use App\Components\SendMessageManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Components\Utils;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class UserUpController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $userUps = UserUpManager::getListByStatusPaginate(["0"]);
//        dd($userUps);
        foreach ($userUps as $userUp) {
            $userUp = UserUpManager::getUserUpInfoByLevel($userUp, 0);
        }
        return view('admin.userUp.index', ['admin' => $admin, 'datas' => $userUps]);
    }

    //进行加盟申请的搜索
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $userUps = null;
//        dd($data['search_status']);
        //如果不存在search_status，代表搜索全部
        if (!array_key_exists('search_status', $data) || Utils::isObjNull($data['search_status'])) {
            $userUps = UserUpManager::getListByStatusPaginate(["0", "1", "2"]);
        } else {
            $userUps = UserUpManager::getListByStatusPaginate([$data['search_status']]);
        }
        foreach ($userUps as $userUp) {
            $userUp = UserUpManager::getUserUpInfoByLevel($userUp, 0);
        }
        return view('admin.userUp.index', ['admin' => $admin, 'datas' => $userUps]);
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
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //status必须为1-审核通过或者2-审核不通过
        $status = $data['status'];
        if (!($status == '1' || $status == '2')) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数,opt必须为0或者1，现值为' . $opt]);
        }
        $userUp = UserUpManager::getById($id);
        $userUp->status = $status;
        $userUp->admin_id = $data['admin_id'];
        $userUp->sh_time = DateTool::getCurrentTime();
        $userUp->save();
        //如果审核通过，则设置用户身份
        if ($userUp->status == "1") {
            $user = UserManager::getByIdWithToken($userUp->user_id);    //此处不要丢失token
            $user->role = "1";
            $user->save();
            //并发送消息通知
            $house = HouseManager::getById($userUp->house_id);
            $message_content = [
                'keyword1' => $house->title,
                'keyword2' => $userUp->sh_time
            ];
//            dd($message_content);
            SendMessageManager::sendMessage($user->id, SendMessageManager::USERUP_SUCCESS, $message_content);
        }
        return redirect('/admin/userUp/index');
    }
}