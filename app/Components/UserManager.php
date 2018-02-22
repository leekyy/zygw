<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */
namespace App\Components;

use App\Models\Exange;
use App\Models\User;
use App\Models\Goods;
use App\Models\Rules;
use App\Models\UserUp;
use Illuminate\Support\Facades\DB;
use App\Models\Pingjia;

class UserManager
{
    /*
     * 根据id获取用户信息，带token
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getByIdWithToken($user_id)
    {
        $user = User::find($user_id);
        return $user;
    }


    /*
     * 增加中介报备次数
     *
     * By TerryQi
     *
     */
    public static function addBaobeiTimes($user_id)
    {
        $user = self::getByIdWithToken($user_id);
        if ($user) {
            $user->baobei_times += 1;
            $user->save();
        }
    }


    /*
     * 根据搜索关键字和身份获取用户信息-分页
     *
     *
     * By TerryQi
     *
     * 2018-01-20
     */
    public static function getListByRoleAndSearchWordPaginate($search_word, $role_arr)
    {
        $users = User::wherein('role', $role_arr)->orderby('id', 'desc')
            ->where('phonenum', 'like', '%' . $search_word . '%')->paginate(Utils::PAGE_SIZE);
        return $users;
    }

    /*
     * 根据id获取用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getById($id)
    {
        $user = User::where('id', '=', $id)->first();
        if ($user) {
            $user->token = null;
        }
        return $user;
    }

    /*
     * 根据手机号获取用户信息
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public static function getByTel($phonenum)
    {
        $user = User::where('phonenum', '=', $phonenum)->first();
        return $user;
    }

    /*
     * 根据user_code和token校验合法性，全部插入、更新、删除类操作需要使用中间件
     *
     * By TerryQi
     *
     * 2017-09-14
     *
     * 返回值
     *
     */
    public static function checkToken($id, $token)
    {
        //根据id、token获取用户信息
        $count = User::where('id', '=', $id)->where('token', '=', $token)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 用户登录
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function login($data)
    {
        //获取account_type，后续进行登录类型判断
        $account_type = $data['account_type'];
        // 判断小程序，按照类型查询
        if ($account_type === 'xcx') {
            $user = self::getByXCXOpenId($data['xcx_openid']);
            //存在用户即返回用户信息
            if ($user != null) {
                return $user;
            }
        }
        //不存在即新建用户
        return self::register($data);
    }

    /*
     * 配置用户信息，用于更新用户信息和新建用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function setUser($user, $data)
    {
        if (array_key_exists('nick_name', $data)) {
            $user->nick_name = array_get($data, 'nick_name');
        }
        if (array_key_exists('real_name', $data)) {
            $user->real_name = array_get($data, 'real_name');
        }
        if (array_key_exists('avatar', $data)) {
            $user->avatar = array_get($data, 'avatar');
        }
        if (array_key_exists('phonenum', $data)) {
            $user->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('xcx_openid', $data)) {
            $user->xcx_openid = array_get($data, 'xcx_openid');
        }
        if (array_key_exists('fwh_openid', $data)) {
            $user->fwh_openid = array_get($data, 'fwh_openid');
        }
        if (array_key_exists('unionid', $data)) {
            $user->unionid = array_get($data, 'unionid');
        }
        if (array_key_exists('gender', $data)) {
            $user->gender = array_get($data, 'gender');
        }
        if (array_key_exists('status', $data)) {
            $user->status = array_get($data, 'status');
        }
        if (array_key_exists('token', $data)) {
            $user->token = array_get($data, 'token');
        }
        if (array_key_exists('role', $data)) {
            $user->role = array_get($data, 'role');
        }
        if (array_key_exists('province', $data)) {
            $user->province = array_get($data, 'province');
        }
        if (array_key_exists('city', $data)) {
            $user->city = array_get($data, 'city');
        }
        if (array_key_exists('jifen', $data)) {
            $user->jifen = array_get($data, 'jifen');
        }
        if (array_key_exists('yongjin', $data)) {
            $user->yongjin = array_get($data, 'yongjin');
        }
        if (array_key_exists('baobei_times', $data)) {
            $user->baobei_times = array_get($data, 'baobei_times');
        }
        if (array_key_exists('cardID', $data)) {
            $user->cardID = array_get($data, 'cardID');
        }
        if (array_key_exists('re_user_id', $data)) {
            $user->re_user_id = array_get($data, 're_user_id');
        }
        return $user;
    }

    /*
     * 注册用户
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function register($data)
    {
        //创建用户信息
        $user = new User();
        $user = self::setUser($user, $data);
        $user->token = self::getGUID();
        $user->save();
        $user = self::getByIdWithToken($user->id);
        return $user;
    }

    /*
     * 更新用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function updateById($user_id, $data)
    {
        //配置用户信息
        $user = UserManager::getByIdWithToken($user_id);
        $user = self::setUser($user, $data);
        $user->save();
        return $user;
    }

    /*
     * 根据用户openid获取用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getByXCXOpenId($openid)
    {
        $user = User::where('xcx_openid', '=', $openid)->first();
        return $user;
    }


    /*
     * 根据用户unionid获取用户信息
     *
     * By TerryQi
     *
     * 2018-02-22
     *
     */
    public static function getByUnionid($unionid)
    {
        $user = User::where('unionid', '=', $unionid)->first();
        return $user;
    }


    /*
    * 服务号注册用户流程
    *
    * By TerryQi
    *
    * 2018-01-17
    */
    public static function registerFWH($data)
    {
        $unionid = $data['unionid'];
        $user = self::getByUnionid($unionid);
        //如果存在用户，则说明已经关注服务号，只需赋值xcx_openid即可
        if ($user) {
            $user->fwh_openid = $data['openid'];
            $user->save();
        } else {
            //创建用户信息
            $user = new User();
            $user = self::setUser($user, $data);
            $user->token = self::getGUID();
            $user->save();
        }
        $user = self::getByIdWithToken($user->id);
        return $user;
    }



    // 生成guid
    /*
     * 生成uuid全部用户相同，uuid即为token
     *
     */
    public static function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $uuid = substr($charid, 0, 8)
                . substr($charid, 8, 4)
                . substr($charid, 12, 4)
                . substr($charid, 16, 4)
                . substr($charid, 20, 12);
            return $uuid;
        }
    }

    /*
     * 根据house_id获取全部生效的案场负责人列表
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function getValidACFZRsByHouseId($house_id)
    {
        $user_ids = array();
        $userUps = UserUp::where('house_id', '=', $house_id)->where('status', '=', '1')->get();
        foreach ($userUps as $userUp) {
            array_push($user_ids, $userUp->user_id);
        }
        $users = User::where('status', '=', '1')->where('role', '=', '1')->wherein('id', $user_ids)->get();
        return $users;
    }

    /*
     * 用户是否在案场负责人列表中
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public static function isUserInACFZRs($user_id, $acfzrs)
    {
        foreach ($acfzrs as $acfzr) {
            if ($acfzr->id == $user_id) {
                return true;
            }
        }
        return false;
    }
}