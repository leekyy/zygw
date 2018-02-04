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
use Illuminate\Support\Collection;
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
        $pay_way = BaobeiPayWay::where('status', '=', '1')->orderby('id', 'asc')->get();
        $buy_purpose = BaobeiBuyPurpose::where('status', '=', '1')->orderby('id', 'asc')->get();
        $know_way = BaobeiKnowWay::where('status', '=', '1')->orderby('id', 'asc')->get();
        $client_care = BaobeiClientCare::where('status', '=', '1')->orderby('id', 'asc')->get();

        $baobeiOption = new Collection([
            'pay_way' => $pay_way,
            'buy_purpose' => $buy_purpose,
            'know_way' => $know_way,
            'client_care' => $client_care
        ]);
        return $baobeiOption;
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
        if (array_key_exists('user_id', $data)) {
            $baobei->user_id = array_get($data, 'user_id');
        }
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
        if (array_key_exists('pay_zhongie_status', $data)) {
            $baobei->pay_zhongie_status = array_get($data, 'pay_zhongie_status');
        }
        if (array_key_exists('pay_zhongjie_time', $data)) {
            $baobei->pay_zhongjie_time = array_get($data, 'pay_zhongjie_time');
        }
        if (array_key_exists('pay_zhongie_attach', $data)) {
            $baobei->pay_zhongie_attach = array_get($data, 'pay_zhongie_attach');
        }
        return $baobei;
    }
}