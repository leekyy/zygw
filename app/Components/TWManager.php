<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\Article;
use App\Models\TWStep;
use App\Models\TWInfo;
use App\Models\HeZuoTWStep;
use Qiniu\Auth;

class TWManager
{


    /*获取全部的图文类型

     * By Yinyue
     *
     * 2018-2-27
     */

    public static  function getIndexAll(){
        $tw = TWInfo::where('status','=','0')->orderby('seq','desc')->get();
        return $tw;
    }


    /*
     * 根据id获取信息
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getById($id)
    {
        $tw = TWInfo::where('id', '=', $id)->first();
        return $tw;
    }

    /*根据type属性获取图文类型
     * By Yinyue
     * 2017-2-26
     */
    public static function getByType($type)
    {
        $tw = TWInfo::where('type', '=', $type)->first();
        $tw->tw_steps = TWStepManager::getStepsByFidAndFtable($tw->id,'tw');
        return $tw;
    }


    /*
     * 根据宣教和level获取信息
     *
     * By TerryQi
     *
     * 2017-12-24
     */
    public static function getInfoById($info, $level)
    {
        $info->step =
        $tw = self::getById($info);
        if ($tw) {
            $tw = self::getXJInfoByLevel($tw, $level);
        }
        return $tw;
    }


    /*
     * 获取信息
     *
     * By TerryQi
     *
     * 2017-12-20
     */
    public static function getList()
    {
        $tw = TWInfo::orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $tw;
    }


    /*
     * 设置相关信息
     *
     * By TerryQi
     *
     * 2017-12-6
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('title', $data)) {
            $info->title = array_get($data, 'title');
        }
        if (array_key_exists('desc', $data)) {
            $info->desc = array_get($data, 'desc');
        }
        if (array_key_exists('author', $data)) {
            $info->author = array_get($data, 'author');
        }
        if (array_key_exists('img', $data)) {
            $info->img = array_get($data, 'img');
        }
        if (array_key_exists('seq', $data)) {
            $info->seq = array_get($data, 'seq');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }

        if (array_key_exists('show_num', $data)) {
            $info->show_num = array_get($data, 'show_num');
        }
        return $info;
    }
}
