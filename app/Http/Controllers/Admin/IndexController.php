<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\BaobeiManager;
use App\Components\Utils;
use App\Models\Baobei;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use Illuminate\Support\Collection;

class IndexController
{
    //首页
    public function index(Request $request)
    {
        $data = $request->all();
        $serverInfo = ServerUtils::getServerInfo();
        $admin = $request->session()->get('admin');
        //检索的时间段
        $start_date = date("Y-m-d", strtotime("-30 day"));
        $end_date = date("Y-m-d", time());
        if (array_key_exists('start_date', $data) && !Utils::isObjNull($data['start_date'])) {
            $start_date = $data['start_date'];
        }
        if (array_key_exists('end_date', $data) && !Utils::isObjNull($data['end_date'])) {
            $end_date = $data['end_date'];
        }

        //报备维度统计信息
        $baobei_stmt = array([
            "all" => BaobeiManager::getBaobeiStmtByStatus(null, null, null, null, $start_date, $end_date),
            "baobei_status0" => BaobeiManager::getBaobeiStmtByStatus(['0'], null, null, null, $start_date, $end_date),
            "baobei_status1" => BaobeiManager::getBaobeiStmtByStatus(['1'], null, null, null, $start_date, $end_date),
            "baobei_status2" => BaobeiManager::getBaobeiStmtByStatus(['2'], null, null, null, $start_date, $end_date),
            "baobei_status3" => BaobeiManager::getBaobeiStmtByStatus(['3'], null, null, null, $start_date, $end_date),
            "baobei_status4" => BaobeiManager::getBaobeiStmtByStatus(['4'], null, null, null, $start_date, $end_date),
        ]);
        //报备趋势
        $daofang_trend = BaobeiManager::getDaofangTrend(null, $start_date, $end_date);
//        dd($daofang_trend);
        //结算维度统计信息
        $jiesuan_stmt = array([
            "all" => BaobeiManager::getBaobeiStmtByStatus(['2', '3', '4'], null, null, null, $start_date, $end_date),
            "can_jiesuan_status0" => BaobeiManager::getBaobeiStmtByStatus(['2', '3', '4'], ['0'], null, null, $start_date, $end_date),
            "can_jiesuan_status1" => BaobeiManager::getBaobeiStmtByStatus(['2', '3', '4'], ['1'], null, null, $start_date, $end_date),
            "pay_zhongjie_status0" => BaobeiManager::getBaobeiStmtByStatus(['2', '3', '4'], ['1'], ['0'], null, $start_date, $end_date),
            "pay_zhongjie_status1" => BaobeiManager::getBaobeiStmtByStatus(['2', '3', '4'], ['1'], ['1'], null, $start_date, $end_date),
        ]);
        //佣金信息
        $yongjin_stmt = array([
            "all" => BaobeiManager::getYongjinStmtByStatus(['2', '3', '4'], null, null, null, $start_date, $end_date),
            "can_jiesuan_status0" => BaobeiManager::getYongjinStmtByStatus(['2', '3', '4'], ['0'], null, null, $start_date, $end_date),
            "can_jiesuan_status1" => BaobeiManager::getYongjinStmtByStatus(['2', '3', '4'], ['1'], null, null, $start_date, $end_date),
            "pay_zhongjie_status0" => BaobeiManager::getYongjinStmtByStatus(['2', '3', '4'], ['1'], ['0'], null, $start_date, $end_date),
            "pay_zhongjie_status1" => BaobeiManager::getYongjinStmtByStatus(['2', '3', '4'], ['1'], ['1'], null, $start_date, $end_date),
        ]);

        //佣金相关趋势
        $yongjin_trend = array([
            'shengcheng_yongjin' => BaobeiManager::getShengchengYongjinTrend(null, $start_date, $end_date),
            'queren_yongjin' => BaobeiManager::getQueRenYongjinTrend(null, $start_date, $end_date),
            'zhifu_yongjin' => BaobeiManager::getZhiFuYongjinTrend(null, $start_date, $end_date)]);

        return view('admin.index.index', ['serverInfo' => $serverInfo, 'admin' => $admin
            , 'start_date' => $start_date
            , 'end_date' => $end_date
            , 'baobei_stmt' => \GuzzleHttp\json_encode($baobei_stmt)
            , 'daofang_trend' => \GuzzleHttp\json_encode($daofang_trend)
            , 'jiesuan_stmt' => \GuzzleHttp\json_encode($jiesuan_stmt)
            , 'yongjin_stmt' => \GuzzleHttp\json_encode($yongjin_stmt)
            , 'yongjin_trend' => \GuzzleHttp\json_encode($yongjin_trend)]);
    }

    //错误
    public function error(Request $request)
    {
        $data = $request->all();
        $msg = null;
        if (array_key_exists('msg', $data)) {
            $msg = $data['msg'];
        }
        $admin = $request->session()->get('admin');
        return view('admin.index.error500', ['msg' => $msg, 'admin' => $admin]);
    }
}