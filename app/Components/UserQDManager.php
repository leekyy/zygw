<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\UserQD;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class UserQDManager
{
    /*
     * 根据用户id进行签到
     *
     * By TerryQi
     *
     * 2018-1-21
     *
     */
    public static function getQDListByUserIdPaginate($user_id)
    {
        $userQDs = UserQD::where('user_id', '=', $user_id)->orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $userQDs;
    }


    /*
   * 根据手机号获取姓名搜索
   *
   * By TerryQi
   *
   * 2018-02-19
   *
   */
    public static function search($phonenum)
    {
        $user = User::where('phonenum', '=', $phonenum)->orderBy('id', 'desc')->first();
        return $user;
    }
    /*
     * 根据id获取签到信息详情
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public static function getUserQDById($id)
    {

        $userQD = UserQD::where('id', '=', $id)->first();
        return $userQD;
    }

    /*
     * 判断该用户今日是否签到过
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function isUserAlreadyQDToday($user_id)
    {
        $today = DateTool::getToday();
        $tomorrow = DateTool::dateAdd('D', 1, $today);
        $userQD = UserQD::where('user_id', '=', $user_id)->where('created_at', '>', $today)->where('created_at', '<', $tomorrow)->first();
        return $userQD;
    }


    /*
     * 获取全部签到明细列表
     *
     * By TerryQI
     *
     * 2017-12-04
     *
     */
    public static function getAllUserQDsPaginate()
    {
        $userQDs = UserQD::orderBy('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $userQDs;
    }

    /*
     * 根据用户签到获取详情
     *
     * By TerryQi
     *
     * 2017-01-21
     */
    public static function getUserQDInfoByLevel($userQD, $level)
    {
        $userQD->user = UserManager::getById($userQD->user_id);
        return $userQD;
    }


    /*
     * 设置用户签到信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setUserQD($userQD, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $userQD->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('jifen', $data)) {
            $userQD->jifen = array_get($data, 'jifen');
        }
        return $userQD;
    }


    /*
     * 获取签到总人次数
     *
     * By TerryQi
     */
    public static function getAllQDRenCiShuNum()
    {
        $count = UserQD::all()->count();
        return $count;
    }

    /*
     * 获取签到总人数
     *
     * By TerryQi
     */
    public static function getAllQDRenShuNum()
    {
        $count = DB::select('SELECT COUNT(distinct user_id) as rs FROM zygwdb.t_user_qd;', []);
        return $count[0]->rs;
    }


    /*
     * 获取派送积分总额
     *
     * By TerryQi
     *
     */
    public static function getAllPaiSongJiFenNum()
    {
        $count = DB::select('SELECT SUM(jifen)  as jf FROM zygwdb.t_user_qd;', []);
        return $count[0]->jf;
    }

    /*
     * 获取近N日的报表
     *
     * By TerryQi
     *
     */
    public static function getRecentDatas($day_num)
    {
        $data = DB::select('SELECT DATE_FORMAT( created_at, "%Y-%m-%d" ) as tjdate , COUNT(*)  as qdrs, SUM(jifen)  as psjfs FROM zygwdb.t_user_qd GROUP BY tjdate order by tjdate desc limit 0,:day_num;', ['day_num' => $day_num]);
        return $data;
    }


}