<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\Article;
use App\Models\HeZuo;
use App\Models\HeZuoTWStep;
use App\Models\TWStep;
use Qiniu\Auth;

class TWStepManager
{
    /*
     * 根据f_id和f_table获取信息
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getStepsByFidAndFtable($f_id, $f_table)
    {
        $steps = TWStep::where('f_table', '=', $f_table)->where('f_id', '=', $f_id)->orderby('seq', 'asc')->get();
        return $steps;
    }

    /*
     * 设置步骤信息
     *
     * By TerryQi
     *
     * 2017-12-07
     *
     */
    public static function setInfo($tw_step, $data)
    {
        if (array_key_exists('f_id', $data)) {
            $tw_step->f_id = array_get($data, 'f_id');
        }
        if (array_key_exists('f_table', $data)) {
            $tw_step->f_table = array_get($data, 'f_table');
        }
        if (array_key_exists('img', $data)) {
            $tw_step->img = array_get($data, 'img');
        }
        if (array_key_exists('video', $data)) {
            $tw_step->video = array_get($data, 'video');
        }
        if (array_key_exists('text', $data)) {
            $tw_step->text = array_get($data, 'text');
        }
        if (array_key_exists('seq', $data)) {
            $tw_step->seq = array_get($data, 'seq');
        }
        return $tw_step;
    }
}
