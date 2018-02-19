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
use App\Components\BaobeiManager;
use App\Components\ClientManager;
use App\Components\KeHuManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\KH;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Collection;
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
        $clients = ClientManager::getListPaginate();
        foreach ($clients as $client) {
            $client = ClientManager::getInfoByLevel($client, '0');
        }
//        dd($clients);
        return view('admin.client.index', ['admin' => $admin, 'datas' => $clients]);
    }

    //综合统计页面
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
        $client = ClientManager::getById($data['id']);
        $datas = BaobeiManager::getListForClientByStatusPaginate($data['id'], $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no);
        foreach ($datas as $data) {
            $data = BaobeiManager::getInfoByLevel($data, "0");
        }

        //获取统计信息
        $stmt = new Collection([
            'all_nums' => BaobeiManager::getListForClientByStatus($client->id, null, null, null, null, null, null)->count(),
            'baobei_status0' => BaobeiManager::getListForClientByStatus($client->id, '0', null, null, null, null, null)->count(),
            'baobei_status1' => BaobeiManager::getListForClientByStatus($client->id, '1', null, null, null, null, null)->count(),
            'baobei_status2' => BaobeiManager::getListForClientByStatus($client->id, '2', null, null, null, null, null)->count(),
            'baobei_status3' => BaobeiManager::getListForClientByStatus($client->id, '3', null, null, null, null, null)->count(),
            'baobei_status4' => BaobeiManager::getListForClientByStatus($client->id, '4', null, null, null, null, null)->count(),
            'can_jiesuan_status1' => BaobeiManager::getListForClientByStatus($client->id, null, '1', null, null, null, null)->count(),
            'pay_zhongjie_status1' => BaobeiManager::getListForClientByStatus($client->id, null, null, '1', null, null, null)->count(),
        ]);
//        dd($stmt);
        return view('admin.client.stmt', ['admin' => $admin, 'client' => $client, 'stmt' => $stmt, 'datas' => $datas]);
    }


    /*
     * 搜索客户页面
     *
     * By TerryQi
     *
     * 2018-02-19
     */
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $search_word = "";
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        $clients = ClientManager::search($search_word);
        foreach ($clients as $client) {
            $client = ClientManager::getInfoByLevel($client, '0');
        }
        return view('admin.client.index', ['admin' => $admin, 'datas' => $clients]);
    }
}