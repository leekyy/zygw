<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\HeZuo;
use App\Models\WhiteBook;
use App\Models\Rules;
use Qiniu\Auth;

class RuleManager
{
    /*
     * 获取首页生效的广告轮播图信息
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getADsForIndex()
    {
        $ads = Rules::where('status', '=', '0')->orderby('id', 'desc')->get();
        return $ads;
    }

    /*
     * 根据id获取合作细则
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     */
    public static function getADById($id)
    {
        $ad = Rules::where('id', '=', $id)->first();
        return $ad;
    }

    public static function getById($id)
    {
        $house = Rules::where('id', '=', $id)->first();
        return $house;
    }

    /*
     * 获取全部未删除的广告图
     *
     * By TerryQI
     *
     * 2017-12-04
     *
     */
    public static function getAllADs()
    {
        $ads = Rules::orderBy('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $ads;
    }


    /*
     * 设置广告信息，用于编辑、
     *
     * By TerryQi
     *
     */
    public static function setAD($ad, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $ad->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('content', $data)) {
            $ad->content = array_get($data, 'content');
        }
        if (array_key_exists('jiangli', $data)) {
            $ad->jiangli = array_get($data, 'jiangli');
        }
        if (array_key_exists('kouchu', $data)) {
            $ad->kouchu = array_get($data, 'kouchu');
        }
        if (array_key_exists('duihuan', $data)) {
            $ad->duihuan = array_get($data, 'duihuan');
        }
        if (array_key_exists('status', $data)) {
            $ad->status = array_get($data, 'status');
        }
        return $ad;
    }
}