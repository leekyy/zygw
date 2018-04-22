<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Baobei;
use App\Models\BaobeiBuyPurpose;
use App\Models\BaobeiClientCare;
use App\Models\BaobeiKnowWay;
use App\Models\BaobeiPayWay;
use App\Models\HouseArea;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class BaobeiManager
{
    /*
     * 获取全部报备信息
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function getBaobeiOptions()
    {
        $pay_ways = BaobeiPayWayManager::getListValid();
        $buy_purposes = BaobeiBuyPurposeManager::getListValid();
        $know_ways = BaobeiKnowWayManager::getListValid();
        $client_cares = BaobeiClientCareManager::getListValid();
        $areas = HouseAreaManager::getList();

        $baobeiOption = new Collection([
            'pay_ways' => $pay_ways,
            'buy_purposes' => $buy_purposes,
            'know_ways' => $know_ways,
            'client_cares' => $client_cares,
            'areas' => $areas,
        ]);
        return $baobeiOption;
    }


    /*
     * 获取楼盘列表下待案场负责人接收的楼盘列表
     *
     * By TerryQi
     *
     * 2018-02-09
     *
     */
    public static function getWaitingForAccpectByHouseIds($house_ids_arr)
    {
//        dd($house_ids_arr);
        $house_arr_str = "(" . implode(',', $house_ids_arr) . ")";
        $baobeis = DB::select("SELECT * FROM zygwdb.t_baobei_info where baobei_status in ('0','1') and status = '1' and anchang_id is null and house_id in " . $house_arr_str . " order by id desc;");
        return $baobeis;
    }


    /*
     * 根据visit_way获取中文描述
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function getVisitWayTxt($visit_way)
    {
        if ($visit_way == '0') {
            return "中介带领";
        } else {
            return "自行到访";
        }
    }


    /*
     * 根据id获取报备详情
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function getById($id)
    {
        $baobei = Baobei::find($id);
        return $baobei;
    }

    /*
     * 获取报备详细信息
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public static function getInfoByLevel($baobei, $level)
    {
        $baobei->client = ClientManager::getById($baobei->client_id);
        $baobei->user = UserManager::getById($baobei->user_id);
        $baobei->house = HouseManager::getById($baobei->house_id);
        //案场负责人
        if (!Utils::isObjNull($baobei->anchang_id)) {
            $baobei->anchang = UserManager::getById($baobei->anchang_id);
        }
        //置业顾问
        if (!Utils::isObjNull($baobei->guwen_id)) {
            $baobei->guwen = ZYGWManager::getById($baobei->guwen_id);
        }
        //区域
        if (!Utils::isObjNull($baobei->area_id)) {
            $baobei->area = HouseAreaManager::getById($baobei->area_id);
        }
        //认知途径
        if (!Utils::isObjNull($baobei->way_id)) {
            $baobei->know_way = BaobeiKnowWayManager::getById($baobei->way_id);
        }
        //购房目的
        if (!Utils::isObjNull($baobei->purpose_id)) {
            $baobei->purpose = BaobeiBuyPurposeManager::getById($baobei->purpose_id);
        }
        //关注点
        if (!Utils::isObjNull($baobei->care_id)) {
            $baobei->care = BaobeiClientCareManager::getById($baobei->care_id);
        }
        //产品信息
        if (!Utils::isObjNull($baobei->deal_huxing_id)) {
            $baobei->deal_huxing = HuxingManager::getById($baobei->deal_huxing_id);
        }
        //支付方式
        if (!Utils::isObjNull($baobei->pay_way_id)) {
            $baobei->pay_way = BaobeiPayWayManager::getById($baobei->pay_way_id);
        }
        //支付中介的管理员
        if (!Utils::isObjNull($baobei->pay_admin_id)) {
            $baobei->admin = AdminManager::getAdminInfoById($baobei->pay_admin_id);
        }
        return $baobei;
    }

    /*
     * 判断客户是否已经报备过
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function isClientAlreadyBaobeiByHouseId($client_id, $house_id)
    {
        $baobei = Baobei::where('status', '=', '1')->where('client_id', '=', $client_id)->where('house_id', '=', $house_id)->first();
        return $baobei;
    }


    /*
     * 设置报备信息，用于编辑
     *
     * By TerryQi
     *
     * 2018-02-03
     *
     */
    public static function setBaoBei($baobei, $data)
    {
        if (array_key_exists('trade_no', $data)) {
            $baobei->trade_no = array_get($data, 'trade_no');
        }
        if (array_key_exists('client_id', $data)) {
            $baobei->client_id = array_get($data, 'client_id');
        }
//        if (array_key_exists('user_id', $data)) {         //不能在此处设置user_id，即中介的id，否则多处接口将收到影响
//            $baobei->user_id = array_get($data, 'user_id');
//        }
        if (array_key_exists('house_id', $data)) {
            $baobei->house_id = array_get($data, 'house_id');
        }
        if (array_key_exists('anchang_id', $data)) {
            $baobei->anchang_id = array_get($data, 'anchang_id');
        }
        if (array_key_exists('guwen_id', $data)) {
            $baobei->guwen_id = array_get($data, 'guwen_id');
        }
        if (array_key_exists('area_id', $data)) {
            $baobei->area_id = array_get($data, 'area_id');
        }
        if (array_key_exists('address', $data)) {
            $baobei->address = array_get($data, 'address');
        }
        if (array_key_exists('size', $data)) {
            $baobei->size = array_get($data, 'size');
        }
        if (array_key_exists('status', $data)) {
            $baobei->status = array_get($data, 'status');
        }
        if (array_key_exists('baobei_status', $data)) {
            $baobei->baobei_status = array_get($data, 'baobei_status');
        }
        if (array_key_exists('way_id', $data)) {
            $baobei->way_id = array_get($data, 'way_id');
        }
        if (array_key_exists('purpose_id', $data)) {
            $baobei->purpose_id = array_get($data, 'purpose_id');
        }
        if (array_key_exists('care_id', $data)) {
            $baobei->care_id = array_get($data, 'care_id');
        }
        if (array_key_exists('yongjin', $data)) {
            $baobei->yongjin = array_get($data, 'yongjin');
        }
        if (array_key_exists('intention_status', $data)) {
            $baobei->intention_status = array_get($data, 'intention_status');
        }
        if (array_key_exists('remark', $data)) {
            $baobei->remark = array_get($data, 'remark');
        }
        if (array_key_exists('plan_visit_time', $data)) {
            $baobei->plan_visit_time = array_get($data, 'plan_visit_time');
        }
        if (array_key_exists('visit_way', $data)) {
            $baobei->visit_way = array_get($data, 'visit_way');
        }
        if (array_key_exists('visit_time', $data)) {
            $baobei->visit_time = array_get($data, 'visit_time');
        }
        if (array_key_exists('visit_attach', $data)) {
            $baobei->visit_attach = array_get($data, 'visit_attach');
        }
        if (array_key_exists('deal_time', $data)) {
            $baobei->deal_time = array_get($data, 'deal_time');
        }
        if (array_key_exists('deal_size', $data)) {
            $baobei->deal_size = array_get($data, 'deal_size');
        }
        if (array_key_exists('deal_price', $data)) {
            $baobei->deal_price = array_get($data, 'deal_price');
        }
        if (array_key_exists('deal_huxing_id', $data)) {
            $baobei->deal_huxing_id = array_get($data, 'deal_huxing_id');
        }
        if (array_key_exists('deal_room', $data)) {
            $baobei->deal_room = array_get($data, 'deal_room');
        }
        if (array_key_exists('pay_way_id', $data)) {
            $baobei->pay_way_id = array_get($data, 'pay_way_id');
        }
        if (array_key_exists('sign_time', $data)) {
            $baobei->sign_time = array_get($data, 'sign_time');
        }
        if (array_key_exists('qkdz_time', $data)) {
            $baobei->qkdz_time = array_get($data, 'qkdz_time');
        }
        if (array_key_exists('can_jiesuan_status', $data)) {
            $baobei->can_jiesuan_status = array_get($data, 'can_jiesuan_status');
        }
        if (array_key_exists('can_jiesuan_time', $data)) {
            $baobei->can_jiesuan_time = array_get($data, 'can_jiesuan_time');
        }
        if (array_key_exists('pay_zhongjie_status', $data)) {
            $baobei->pay_zhongjie_status = array_get($data, 'pay_zhongjie_status');
        }
        if (array_key_exists('pay_zhongjie_time', $data)) {
            $baobei->pay_zhongjie_time = array_get($data, 'pay_zhongjie_time');
        }
        if (array_key_exists('pay_admin_id', $data)) {
            $baobei->pay_admin_id = array_get($data, 'pay_admin_id');
        }
        if (array_key_exists('pay_zhongjie_attach', $data)) {
            $baobei->pay_zhongjie_attach = array_get($data, 'pay_zhongjie_attach');
        }
        return $baobei;
    }


    /*
     * 根据状态获取报备列表-不分页
     *
     * By TerryQi
     *
     * 2018-02-04
     *
     */
    public static function getListByStatus($baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $house_id, $start_time, $end_time)
    {
        $baobeis = Baobei::wherein('status', ['0', '1']);
        if ($baobei_status != null) {
            $baobeis = $baobeis->where('baobei_status', '=', $baobei_status);
        }
        if ($can_jiesuan_status != null) {
            $baobeis = $baobeis->where('can_jiesuan_status', '=', $can_jiesuan_status);
        }
        if ($pay_zhongjie_status != null) {
            $baobeis = $baobeis->where('pay_zhongjie_status', '=', $pay_zhongjie_status);
        }
        if ($house_id != null) {
            $baobeis = $baobeis->where('house_id', '=', $house_id);
        }
        if ($start_time != null) {
            $baobeis = $baobeis->where('created_at', '>', $start_time);
        }
        if ($end_time != null) {
            $baobeis = $baobeis->where('created_at', '<=', $end_time);
        }
        $baobeis = $baobeis->orderby('id', 'desc')->get();
        return $baobeis;
    }


    /*
       * 根据状态获取报备统计
       *
       * By TerryQi
       *
       * 2018-02-28
       *
       */
    public static function getBaobeiStmtByStatus($baobei_status_arr, $can_jiesuan_status_arr, $pay_zhongjie_status_arr, $house_id, $start_time, $end_time)
    {
        $baobeis = Baobei::wherein('status', ['0', '1']);
        if ($baobei_status_arr != null) {
            $baobeis = $baobeis->wherein('baobei_status', $baobei_status_arr);
        }
        if ($can_jiesuan_status_arr != null) {
            $baobeis = $baobeis->wherein('can_jiesuan_status', $can_jiesuan_status_arr);
        }
        if ($pay_zhongjie_status_arr != null) {
            $baobeis = $baobeis->wherein('pay_zhongjie_status', $pay_zhongjie_status_arr);
        }
        if ($house_id != null) {
            $baobeis = $baobeis->where('house_id', '=', $house_id);
        }
        if ($start_time != null) {
            $baobeis = $baobeis->where('created_at', '>=', $start_time);
        }
        if ($end_time != null) {
            $baobeis = $baobeis->where('created_at', '<', $end_time);
        }
        $count = $baobeis->orderby('id', 'desc')->count();
        return $count;
    }

    /*
     * 报备趋势信息
     *
     * By TerryQi
     *
     * 2018-02-28
     */
    public static function getDaofangTrend($house_id, $start_time, $end_time)
    {
        $sql_str = "SELECT DATE_FORMAT(date_list.date, '%Y-%m-%d') as tjdate , COUNT(*) - 1 as nums FROM zygwdb.t_date_list date_list left join zygwdb.t_baobei_info baobei_info on DATE_FORMAT(date_list.date,'%Y-%m-%d') = DATE_FORMAT(baobei_info.visit_time,'%Y-%m-%d') ";
        $first_con_flag = true;
        if ($house_id != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " house_id = " . $house_id . " ";
        }
        if ($start_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date >= '" . $start_time . "' ";
        }
        if ($end_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date <= '" . $end_time . "' ";
        }
        $sql_str = $sql_str . " GROUP BY tjdate";

//        dd($sql_str); //测试sql用
        $data = DB::select($sql_str);
        return $data;
    }


    /*
    * 成交生成佣金趋势信息
     *
     * By TerryQi
     *
     * 2018-02-28
     */
    public static function getShengchengYongjinTrend($house_id, $start_time, $end_time)
    {
        $sql_str = "SELECT DATE_FORMAT(date_list.date, '%Y-%m-%d') as tjdate , SUM(baobei_info.yongjin) as yongjin FROM zygwdb.t_date_list date_list left join zygwdb.t_baobei_info baobei_info on DATE_FORMAT(date_list.date,'%Y-%m-%d') = DATE_FORMAT(baobei_info.deal_time,'%Y-%m-%d') ";
        $first_con_flag = true;
        if ($house_id != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " house_id = " . $house_id . " ";
        }
        if ($start_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date >= '" . $start_time . "' ";
        }
        if ($end_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date <= '" . $end_time . "' ";
        }
        $sql_str = $sql_str . " GROUP BY tjdate";

//        dd($sql_str); //测试sql用
        $data = DB::select($sql_str);
        return $data;
    }


    /*
    * 确认佣金趋势信息
     *
     * By TerryQi
     *
     * 2018-02-28
     */
    public static function getQueRenYongjinTrend($house_id, $start_time, $end_time)
    {
        $sql_str = "SELECT DATE_FORMAT(date_list.date, '%Y-%m-%d') as tjdate , SUM(baobei_info.yongjin) as yongjin FROM zygwdb.t_date_list date_list left join zygwdb.t_baobei_info baobei_info on DATE_FORMAT(date_list.date,'%Y-%m-%d') = DATE_FORMAT(baobei_info.can_jiesuan_time,'%Y-%m-%d') ";
        $first_con_flag = true;
        if ($house_id != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " house_id = " . $house_id . " ";
        }
        if ($start_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date >= '" . $start_time . "' ";
        }
        if ($end_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date <= '" . $end_time . "' ";
        }
        $sql_str = $sql_str . " GROUP BY tjdate";

//        dd($sql_str); //测试sql用
        $data = DB::select($sql_str);
        return $data;
    }


    /*
   * 支付佣金趋势信息
    *
    * By TerryQi
    *
    * 2018-02-28
    */
    public static function getZhiFuYongjinTrend($house_id, $start_time, $end_time)
    {
        $sql_str = "SELECT DATE_FORMAT(date_list.date, '%Y-%m-%d') as tjdate , SUM(baobei_info.yongjin) as yongjin FROM zygwdb.t_date_list date_list left join zygwdb.t_baobei_info baobei_info on DATE_FORMAT(date_list.date,'%Y-%m-%d') = DATE_FORMAT(baobei_info.pay_zhongjie_time,'%Y-%m-%d') ";
        $first_con_flag = true;
        if ($house_id != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " house_id = " . $house_id . " ";
        }
        if ($start_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date >= '" . $start_time . "' ";
        }
        if ($end_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date <= '" . $end_time . "' ";
        }
        $sql_str = $sql_str . " GROUP BY tjdate";

//        dd($sql_str); //测试sql用
        $data = DB::select($sql_str);
        return $data;
    }


    /*
    * 根据状态获取佣金金额
    *
    * By TerryQi
    *
    * 2018-02-28
    *
    */
    public static function getYongjinStmtByStatus($baobei_status_arr, $can_jiesuan_status_arr, $pay_zhongjie_status_arr, $house_id, $start_time, $end_time)
    {
        $baobeis = Baobei::wherein('status', ['0', '1']);
        if ($baobei_status_arr != null) {
            $baobeis = $baobeis->wherein('baobei_status', $baobei_status_arr);
        }
        if ($can_jiesuan_status_arr != null) {
            $baobeis = $baobeis->wherein('can_jiesuan_status', $can_jiesuan_status_arr);
        }
        if ($pay_zhongjie_status_arr != null) {
            $baobeis = $baobeis->wherein('pay_zhongjie_status', $pay_zhongjie_status_arr);
        }
        if ($house_id != null) {
            $baobeis = $baobeis->where('house_id', '=', $house_id);
        }
        if ($start_time != null) {
            $baobeis = $baobeis->where('created_at', '>', $start_time);
        }
        if ($end_time != null) {
            $baobeis = $baobeis->where('created_at', '<=', $end_time);
        }
        $yongjin = $baobeis->orderby('id', 'desc')->sum('yongjin');
        return $yongjin;
    }


    /*
     * 根据状态获取报备列表-分页
     *
     * By TerryQi
     *
     * 2018-02-04
     *
     */
    public static function getListByStatusPaginate($baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no)
    {
        $baobeis = Baobei::wherein('status', ['0', '1']);
        if ($baobei_status != null) {
            $baobeis = $baobeis->where('baobei_status', '=', $baobei_status);
        }
        if ($can_jiesuan_status != null) {
            $baobeis = $baobeis->where('can_jiesuan_status', '=', $can_jiesuan_status);
        }
        if ($pay_zhongjie_status != null) {
            $baobeis = $baobeis->where('pay_zhongjie_status', '=', $pay_zhongjie_status);
        }
        if ($trade_no != null) {
            $baobeis = $baobeis->where('trade_no', 'like', '%' . $trade_no . '%');
        }
        $baobeis = $baobeis->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $baobeis;
    }


    /*
     * 根据状态获取中介维度的报备列表-不分页
     *
     * By TerryQi
     *
     * 2018-02-04
     *
     */
    public static function getListForZJByStatus($user_id, $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $house_id, $start_time, $end_time)
    {
        $baobeis = Baobei::where('user_id', '=', $user_id);
        if ($baobei_status != null) {
            $baobeis = $baobeis->where('baobei_status', '=', $baobei_status);
        }
        if ($can_jiesuan_status != null) {
            $baobeis = $baobeis->where('can_jiesuan_status', '=', $can_jiesuan_status);
            //只有成交以后的报备单才面临可结算的状态，因此增加baobei_status = 2\3\4
            $baobeis = $baobeis->wherein('baobei_status', ['2', '3', '4']);
        }
        if ($pay_zhongjie_status != null) {
            $baobeis = $baobeis->where('pay_zhongjie_status', '=', $pay_zhongjie_status);
        }
        if ($house_id != null) {
            $baobeis = $baobeis->where('house_id', '=', $house_id);
        }
        if ($start_time != null) {
            $baobeis = $baobeis->where('created_at', '>', $start_time);
        }
        if ($end_time != null) {
            $baobeis = $baobeis->where('created_at', '<=', $end_time);
        }
        $baobeis = $baobeis->orderby('id', 'desc')->get();
        return $baobeis;
    }

    /*
     * 根据状态获取中介维度的报备列表-分页
     *
     * By TerryQi
     *
     * 2018-02-04
     *
     */
    public static function getListForZJByStatusPaginate($user_id, $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no)
    {
        $baobeis = Baobei::where('user_id', '=', $user_id);
        if ($baobei_status != null) {
            $baobeis = $baobeis->where('baobei_status', '=', $baobei_status);
        }
        if ($can_jiesuan_status != null) {
            $baobeis = $baobeis->where('can_jiesuan_status', '=', $can_jiesuan_status);
            //只有成交以后的报备单才面临可结算的状态，因此增加baobei_status = 2\3\4
            $baobeis = $baobeis->wherein('baobei_status', ['2', '3', '4']);
        }
        if ($pay_zhongjie_status != null) {
            $baobeis = $baobeis->where('pay_zhongjie_status', '=', $pay_zhongjie_status);
        }
        if ($trade_no != null) {
            $baobeis = $baobeis->where('trade_no', 'like', '%' . $trade_no . '%');
        }
        $baobeis = $baobeis->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $baobeis;
    }

    /*
     * 根据状态获取案场负责人维度的报备列表-不分页
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public static function getListForACByStatus($anchang_id, $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $house_id, $start_time, $end_time)
    {
        $baobeis = Baobei::where('anchang_id', '=', $anchang_id);
        if ($baobei_status != null) {
            $baobeis = $baobeis->where('baobei_status', '=', $baobei_status);
        }
        if ($can_jiesuan_status != null) {
            $baobeis = $baobeis->where('can_jiesuan_status', '=', $can_jiesuan_status);
            //只有成交以后的报备单才面临可结算的状态，因此增加baobei_status = 2\3\4
            $baobeis = $baobeis->wherein('baobei_status', ['2', '3', '4']);
        }
        if ($pay_zhongjie_status != null) {
            $baobeis = $baobeis->where('pay_zhongjie_status', '=', $pay_zhongjie_status);
        }
        if ($house_id != null) {
            $baobeis = $baobeis->where('house_id', '=', $house_id);
        }
        if ($start_time != null) {
            $baobeis = $baobeis->where('created_at', '>', $start_time);
        }
        if ($end_time != null) {
            $baobeis = $baobeis->where('created_at', '<=', $end_time);
        }

        $baobeis = $baobeis->orderby('id', 'desc')->get();
        return $baobeis;
    }


    /*
     * 根据状态获取案场负责人维度的报备列表-paginate
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public static function getListForACByStatusPaginate($anchang_id, $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no)
    {
        $baobeis = Baobei::where('anchang_id', '=', $anchang_id);
        if ($baobei_status != null) {
            $baobeis = $baobeis->where('baobei_status', '=', $baobei_status);
        }
        if ($can_jiesuan_status != null) {
            $baobeis = $baobeis->where('can_jiesuan_status', '=', $can_jiesuan_status);
            //只有成交以后的报备单才面临可结算的状态，因此增加baobei_status = 2\3\4
            $baobeis = $baobeis->wherein('baobei_status', ['2', '3', '4']);
        }
        if ($pay_zhongjie_status != null) {
            $baobeis = $baobeis->where('pay_zhongjie_status', '=', $pay_zhongjie_status);
        }
        if ($trade_no != null) {
            $baobeis = $baobeis->where('trade_no', 'like', '%' . $trade_no . '%');
        }
        $baobeis = $baobeis->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $baobeis;
    }

    /*
    * 根据状态获取客户维度的报备列表-不分页
    *
    * By TerryQi
    *
    * 2018-02-04
    */
    public static function getListForClientByStatus($client_id, $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $house_id, $start_time, $end_time)
    {
        $baobeis = Baobei::where('client_id', '=', $client_id);
        if ($baobei_status != null) {
            $baobeis = $baobeis->where('baobei_status', '=', $baobei_status);
        }
        if ($can_jiesuan_status != null) {
            $baobeis = $baobeis->where('can_jiesuan_status', '=', $can_jiesuan_status);
        }
        if ($pay_zhongjie_status != null) {
            $baobeis = $baobeis->where('pay_zhongjie_status', '=', $pay_zhongjie_status);
        }
        if ($house_id != null) {
            $baobeis = $baobeis->where('house_id', '=', $house_id);
        }
        if ($start_time != null) {
            $baobeis = $baobeis->where('created_at', '>', $start_time);
        }
        if ($end_time != null) {
            $baobeis = $baobeis->where('created_at', '<=', $end_time);
        }

        $baobeis = $baobeis->orderby('id', 'desc')->get();
        return $baobeis;
    }


    /*
     * 根据状态获取案场负责人维度的报备列表-paginate
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public static function getListForClientByStatusPaginate($client_id, $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no)
    {
        $baobeis = Baobei::where('client_id', '=', $client_id);
        if ($baobei_status != null) {
            $baobeis = $baobeis->where('baobei_status', '=', $baobei_status);
        }
        if ($can_jiesuan_status != null) {
            $baobeis = $baobeis->where('can_jiesuan_status', '=', $can_jiesuan_status);
        }
        if ($pay_zhongjie_status != null) {
            $baobeis = $baobeis->where('pay_zhongjie_status', '=', $pay_zhongjie_status);
        }
        if ($trade_no != null) {
            $baobeis = $baobeis->where('trade_no', 'like', '%' . $trade_no . '%');
        }
        $baobeis = $baobeis->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $baobeis;
    }

    /*
     * 根据状态获取楼盘维度的报备列表
     *
     * By TerryQi
     *
     * 2018-02-19
     */
    public static function getListForHouseByStatus($house_id, $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no)
    {
        $baobeis = Baobei::where('house_id', '=', $house_id);
        if ($baobei_status != null) {
            $baobeis = $baobeis->where('baobei_status', '=', $baobei_status);
        }
        if ($can_jiesuan_status != null) {
            $baobeis = $baobeis->where('can_jiesuan_status', '=', $can_jiesuan_status);
        }
        if ($pay_zhongjie_status != null) {
            $baobeis = $baobeis->where('pay_zhongjie_status', '=', $pay_zhongjie_status);
        }
        if ($trade_no != null) {
            $baobeis = $baobeis->where('trade_no', 'like', '%' . $trade_no . '%');
        }
        $baobeis = $baobeis->orderby('id', 'desc')->get();
        return $baobeis;
    }

    /*
     * 根据状态获取楼盘维度的报备列表-paginate
     *
     * By TerryQi
     *
     * 2018-02-19
     */
    public static function getListForHouseByStatusPaginate($house_id, $baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no)
    {
        $baobeis = Baobei::where('house_id', '=', $house_id);
        if ($baobei_status != null) {
            $baobeis = $baobeis->where('baobei_status', '=', $baobei_status);
        }
        if ($can_jiesuan_status != null) {
            $baobeis = $baobeis->where('can_jiesuan_status', '=', $can_jiesuan_status);
        }
        if ($pay_zhongjie_status != null) {
            $baobeis = $baobeis->where('pay_zhongjie_status', '=', $pay_zhongjie_status);
        }
        if ($trade_no != null) {
            $baobeis = $baobeis->where('trade_no', 'like', '%' . $trade_no . '%');
        }
        $baobeis = $baobeis->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $baobeis;
    }


    /*
   * 获取中介佣金总额
   *
   * By TerryQi
   *
   * 2018-02-04
   */
    public static function getTotalYongjinByUserId($user_id)
    {
        $total_yongjin = Baobei::where('user_id', '=', $user_id)->sum('yongjin');
        return $total_yongjin;
    }

    /*
     * 获取待确认佣金总额
     *
     * By TerryQi
     *
     * 2018-02-04
     *
     */
    public static function getNotCanJiesuanYongjinByUserId($user_id)
    {
        $not_can_jiesuan_yongjin = Baobei::where('user_id', '=', $user_id)->where('can_jiesuan_status', '=', '0')->sum('yongjin');
        return $not_can_jiesuan_yongjin;
    }

    /*
     * 获取带结算佣金总额
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public static function getNotPayYongjinByUserId($user_id)
    {
        $not_pay_yongjin = Baobei::where('user_id', '=', $user_id)->where('can_jiesuan_status', '=', '1')->where('pay_zhongjie_status', '=', '0')->sum('yongjin');
        return $not_pay_yongjin;
    }

    /*
     * 获取已经结算佣金总额
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public static function getAlreadyPayYongjinByUserId($user_id)
    {
        $already_pay_yongjin = Baobei::where('user_id', '=', $user_id)->where('can_jiesuan_status', '=', '1')->where('pay_zhongjie_status', '=', '1')->sum('yongjin');
        return $already_pay_yongjin;
    }


    /*
     * 根据用户id获取全部佣金信息
     *
     * By TerryQi
     *
     * 2018-02-22
     *
     */
    public static function getAllYongjinByUserId($user_id)
    {
        $all_yongjin = Baobei::where('user_id', '=', $user_id)->where('status', '=', '1')->sum('yongjin');
        return $all_yongjin;
    }

    /*
     * 根据用户id获取待结算佣金
     *
     * By TerryQi
     *
     * 2018-02-22
     */
    public static function getWaitingForPayByUserId($user_id)
    {
        $waingtingForPay_yongjin = Baobei::where('user_id', '=', $user_id)->where('status', '=', '1')
            ->where('can_jiesuan_status', '=', '1')->where('pay_zhongjie_status', '=', '0')->sum('yongjin');
        return $waingtingForPay_yongjin;
    }


    //获取报备状态中文
    public static function getBaobeiStr($baobei_status)
    {
        switch ($baobei_status) {
            case '0':
                return "报备";
            case '1':
                return "到访";
            case '2':
                return '成交';
            case '3':
                return "签约";
            case '4':
                return "全款到账";
        }
    }

    //是否可结算状态
    public static function getCanJieSuanStr($can_jiesuan_status)
    {
        switch ($can_jiesuan_status) {
            case '0':
                return "不可结算";
            case '1':
                return "可以结算";
        }
    }

    //是否支付
    public static function getPayZhongJieStr($pay_zhongjie_status)
    {
        switch ($pay_zhongjie_status) {
            case '0':
                return "未支付";
            case '1':
                return "已支付";
        }
    }

    //到访方式
    public static function getVisitWayStr($visit_way)
    {
        switch ($visit_way) {
            case '0':
                return "中介带访";
            case '1':
                return "自行到访";
        }
    }


    //计划任务，获取全部的超期报备数据
    /*
     * 超期逻辑为status==1且baobei_status==0且created_at时间超过当前时间
     *
     */
    public static function getAllBaobeiExceedList()
    {
        $curr = DateTool::getCurrentTime();
        $baobeis = Baobei::where('status', '=', '1')->where('baobei_status', '=', '0')->where('created_at', '<', $curr)->get();
        return $baobeis;
    }

    //计划任务，获取全部的超期成交数据
    /*
     * 超期逻辑为status==1且baobei_status==1且visit_time大于30天
     *
     *
     */
    public static function getAllDealExceedList()
    {
        $curr = DateTool::getCurrentTime();
        $exceed_date = DateTool::dateAdd('D', -30, $curr, null);
//        dd($exceed_date);
        $baobeis = Baobei::where('status', '=', '1')->where('baobei_status', '=', '1')->where('visit_time', '<', $exceed_date)->get();
        return $baobeis;
    }


    /*
     * 中介按照到访量进行排名
     *
     * By TerryQi
     *
     * 2018-03-19
     */
    public static function zhongjiePaiming()
    {
        $sql_str = "SELECT count(*) as num,user_id FROM zygwdb.t_baobei_info where baobei_status in ('1','2','3','4') group by user_id order by num desc;";
        $data = DB::select($sql_str);
        return $data;
    }
}