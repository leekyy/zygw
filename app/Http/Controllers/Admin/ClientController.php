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
use App\Components\KeHuManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\KH;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class ClientController
{

    //首页
    /*
     * By TerrQi
     *
     * 2018-02-09
     */
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');

    }

}