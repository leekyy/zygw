<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */
namespace App\Components;
use App\Models\AD;
use App\Models\House;
use App\Models\Huxing;
use App\Models\HouseDetail;
use Qiniu\Auth;
class HouseDetailManager
{
    /* 根据楼盘id获取楼盘参数
     *
     * By Yinyue
     *
     * 2018-1-22
     */
    public static function getHouseDetailByHouseId($house_id)
    {
        $housedetail = HouseDetail::where('house_id', $house_id)->paginate(Utils::PAGE_SIZE);
        //设置用户信息和楼盘信息
        return $housedetail;
    }
    /*
     * 根据id获取楼盘参数
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $detail = HouseDetail::where('id', '=', $id)->first();
        return $detail;
    }
    /*根据楼盘id获取该楼盘下的所有产品
     *
     * By Yinyue
     * 2018-1-24
     */
    public static function getListByHouseId($house_id)
    {
        $huxings = Huxing::where('house_id', '=', $house_id)->get();
        return $huxings;
    }
    /*
     * 根据楼盘id获取该楼盘下的所有产品
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function getListByHouseIdValid($house_id)
    {
        $huxings = Huxing::where('house_id', '=', $house_id)->where('status', '=', '1')->get();
        return $huxings;
    }
    /*
     * 获取户型详细信息
     *
     * By TerryQi
     */
    public static function getInfoByLevel($huxing, $level)
    {
        $huxing->admin = AdminManager::getAdminInfoById($huxing->admin_id);
        $huxing->type = HouseTypeManager::getById($huxing->type_id);
        return $huxing;
    }
    /*
     * 设置佣金信息
     *
     * By TerryQi
     *
     * 2018-01-31
     *
     */
    public static function setYongjin($huxing, $data)
    {
        if (array_key_exists('set_yongjin_type', $data)) {
            $huxing->yongjin_type = array_get($data, 'set_yongjin_type');
        }
        if (array_key_exists('set_yongjin_value', $data)) {
            $huxing->yongjin_value = array_get($data, 'set_yongjin_value');
        }
        return $huxing;
    }
    /*
     * 获取佣金文字说明
     *
     * By TerryQi
     *
     * 2018-02-01
     */
    public static function getSetYongjinText($yongjin_type, $yongjin_value)
    {
        $text = "设置为 ";
        //佣金类型文字
        if ($yongjin_type == '0') {
            $text = $text . "按固定金额 " . $yongjin_value . "元";
        }
        if ($yongjin_type == '1') {
            $text = $text . "按千分比 " . $yongjin_value . "‰";
        }
        return $text;
    }
    /*
     * 设置户型信息
     *
     * By TerryQi
     *
     * 2018-01-31
     */
    public static function setInfo($detail, $data)
    {
        if (array_key_exists('house_id', $data)) {
            $detail->house_id = array_get($data, 'house_id');
        }
        if (array_key_exists('admin_id', $data)) {
            $detail->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('kaipantime', $data)) {
            $detail->kaipantime = array_get($data, 'kaipantime');
        }
        if (array_key_exists('jiaopantime', $data)) {
            $detail->jiaopantime = array_get($data, 'jiaopantime');
        }
        if (array_key_exists('developer', $data)) {
            $detail->developer = array_get($data, 'developer');
        }
        if (array_key_exists('property', $data)) {
            $detail->property = array_get($data, 'property');
        }
        if (array_key_exists('size', $data)) {
            $detail->size = array_get($data, 'size');
        }
        if (array_key_exists('households', $data)) {
            $detail->households = array_get($data, 'households');
        }
        if (array_key_exists('plotratio', $data)) {
            $detail->plotratio = array_get($data, 'plotratio');
        }
        if (array_key_exists('orientation', $data)) {
            $detail->orientation = array_get($data, 'orientation');
        }
        if (array_key_exists('green', $data)) {
            $detail->green = array_get($data, 'green');
        }
        if (array_key_exists('park', $data)) {
            $detail->park = array_get($data, 'park');
        }
        if (array_key_exists('parkper', $data)) {
            $detail->parkper = array_get($data, 'parkper');
        }
        if (array_key_exists('price', $data)) {
            $detail->price = array_get($data, 'price');
        }
        if (array_key_exists('propertyfee', $data)) {
            $detail->propertyfee = array_get($data, 'propertyfee');
        }
        if (array_key_exists('buildtype', $data)) {
            $detail->buildtype = array_get($data, 'buildtype');
        }
        if (array_key_exists('decorate', $data)) {
            $detail->decorate = array_get($data, 'decorate');
        }
        if (array_key_exists('years', $data)) {
            $detail->years = array_get($data, 'years');
        }
        if (array_key_exists('shangye', $data)) {
            $detail->shangye = array_get($data, 'shangye');
        }
        if (array_key_exists('jiaoyu', $data)) {
            $detail->jiaoyu = array_get($data, 'jiaoyu');
        }
        if (array_key_exists('jiaotong', $data)) {
            $detail->jiaotong = array_get($data, 'jiaotong');
        }
        if (array_key_exists('huanjing', $data)) {
            $detail->huanjing = array_get($data, 'huanjing');
        }
        return $detail;
    }
}