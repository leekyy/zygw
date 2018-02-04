<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Client;
use Qiniu\Auth;

class ClientManager
{

    /*
     * 根据id获取客户信息
     *
     * By TerryQi
     *
     * 2018-01-20
     */
    public static function getById($id)
    {
        $client = Client::find($id);
        return $client;
    }


    /*
     * 根据phonenum获取客户信息
     *
     * By TerryQi
     *
     * 2018-02-01
     */
    public static function getByPhonenum($phonenum)
    {
        $client = Client::where('phonenum', '=', $phonenum)->first();
        return $client;
    }

    /*
     * 获取列表
     *
     * By TerryQi
     */
    public static function getList()
    {
        $clients = Client::orderby('id', 'desc')->get();
        return $clients;
    }

    /*
     * 获取列表-分页
     *
     * By TerryQi
     *
     */
    public static function getListPaginate()
    {
        $clients = Client::orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return $clients;
    }

    /*
     * 增加客户报备次数
     *
     * By TerryQi
     *
     */
    public static function addBaobeiTimes($client_id)
    {
        $client = self::getById($client_id);
        if ($client) {
            $client->baobei_times += 1;
            $client->save();
        }
    }

    /*
     * 设置客户信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setClient($client, $data)
    {
        if (array_key_exists('name', $data)) {
            $client->name = array_get($data, 'name');
        }
        if (array_key_exists('phonenum', $data)) {
            $client->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('status', $data)) {
            $client->status = array_get($data, 'status');
        }
        if (array_key_exists('user_id', $data)) {
            $client->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('baobei_times', $data)) {
            $client->baobei_times = array_get($data, 'baobei_times');
        }
        if (array_key_exists('remark', $data)) {
            $client->remark = array_get($data, 'remark');
        }
        return $client;
    }
}