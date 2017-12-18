<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use Qiniu\Auth;

class ADManager
{

    /*
     * 获取广告图片
     *
     * By yinyue
     *
     * 2017-11-21
     *
     *
     */
    public static function  getADs(){
        $ads = AD::where('state','=','0')->get();
        return $ads;
    }

    /*根据轮播图的id获取详细信息
     *
     * By yinyue
     * 2017-12-13
     */

    public static function getADsInfo($id){
        $ads = AD::where('id','=',$id)->get();
        return $ads;
    }


    /*
     * 设置房源信息，用于编辑、
     *
     * By TerryQi
     *
     */
    public static function setAD($ads, $data)
    {
        if (array_key_exists('title', $data)) {
            $ads->title = array_get($data, 'title');
        }
        if (array_key_exists('img', $data)) {
            $ads->image = array_get($data, 'image');
        }
        if (array_key_exists('url', $data)) {
            $ads->url = array_get($data, 'url');
        }
        if (array_key_exists('type', $data)) {
            $ads->label = array_get($data, 'label');
        }
        if (array_key_exists('seq', $data)) {
            $ads->seq = array_get($data, 'seq');
        }
        if (array_key_exists('status', $data)) {
            $ads->status = array_get($data, 'status');
        }
        return $ads;
    }
}