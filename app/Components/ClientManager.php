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
     * 根据客户号码查询客户信息
     *
     * By TerryQi
     *
     * 2017-11-27
     *
     */
    public static function getByPhonenum($phonenum)
    {
        $client = Client::where('telephone', '=', $phonenum)->first();
        return $client;
    }


    /*
     * 根据id获取客户信息
     *
     *
     *
     */
    public static function getById($id)
    {
        $client = Client::where('id', '=', $id)->first();
        return $client;
    }

    /*
     * 获取全部老客户信息，分页
     *
     *
     */
    public static function getListPaginate()
    {
        $clients = Client::orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
        return;
    }

    /*
     * 获取全部饿虎
     *
     *
     */
    public static function getList()
    {
        $clients = Client::orderby('id', 'desc')->get();
        return;
    }


    /*
     * 设置客户信息，用于编辑、
     *
     * By TerryQi
     *
     */
    public static function setClient($client, $data)
    {
        if (array_key_exists('name', $data)) {
            $client->name = array_get($data, 'name');
        }
        if (array_key_exists('telephone', $data)) {
            $client->telephone = array_get($data, 'telephone');
        }
        return $client;
    }
}