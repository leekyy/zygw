<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


//用户类路由
Route::group(['prefix' => '', 'middleware' => ['BeforeRequest']], function () {
    // 示例接口
    Route::get('test', 'API\TestController@test');
    //获取七牛token
    Route::get('user/getQiniuToken', 'API\UserController@getQiniuToken');

    //根据id获取用户信息
    Route::get('user/getById', 'API\UserController@getUserById');
    //根据id获取用户信息带token
    Route::get('user/getByIdWithToken', 'API\UserController@getUserInfoByIdWithToken')->middleware('CheckToken');
    //根据code获取openid
    Route::get('user/getXCXOpenId', 'API\UserController@getXCXOpenId');
    //登录/注册
    Route::post('user/login', 'API\UserController@login');
    //更新用户信息
    Route::post('user/updateById', 'API\UserController@updateUserById')->middleware('CheckToken');
    //解密encryptedData
    Route::post('user/encryptedData', 'API\UserController@encryptedData');
    //中介升级为案场负责人表
    Route::post('user/applyUp', 'API\UserUpController@userUpApply');
    //获取案场负责人的楼盘
    Route::get('user/getUserUpHousesByUserId', 'API\UserUpController@getUserUpHousesByUserId');

    //用户签到
    Route::post('user/userQDToday', 'API\UserQDController@userQDToday')->middleware('CheckToken');        //用户签到接口
    Route::get('user/getUserQDsByUserId', 'API\UserQDController@getUserQDsByUserId')->middleware('CheckToken');        //根据用户id获取签到列表
    Route::get('user/getRecentDatas', 'Admin\UserQDController@getRecentDatas');        //获取近几日综合统计数据

    //获取广告图
    Route::get('ad/getADs', 'API\ADController@getADs');
    //根据轮播图的id获取相应的信息
    Route::get('ad/getById', 'API\ADController@getADById');

    //获取房源信息
    Route::get('hr/getHRs', 'API\HRController@getHRs');
    //Route::get('hr/getHouse','API\HRController@getHouse');
    Route::get('hr/getIndexPage', 'API\HRController@getIndexPage');
    //根据id获取房源小区楼盘详情
    Route::get('hr/getHRById', 'API\HRController@getHRById');
    //根据小区id获取小区的楼盘参数
    Route::get('hrd/getHDById', 'API\HRController@getHDById');
    //根据小区id获取相对应的户型推荐
    Route::get('hx/getHXById', 'API\HRController@getHXById');
    //根据小区id获取相对应的用户评论
    Route::get('hc/getHCById', 'API\HRController@getHCById');
    //用户对相应楼盘进行评价
    Route::post('hrr/getHouseReview', 'API\HRController@getHouseReview');
    //接收前台提交的信息
    Route::post('kh/getBKH', 'API\KHController@getBKH');
    //修改客户的资料传到后台
    Route::post('kh/getXKH', 'API\KHController@getXKH');
    //获取客户信息
    Route::get('kh/getKHs', 'API\KHController@getKHs');

    Route::post('kh/getSearchKhs', 'API\KHController@getSearchKhs');
    Route::get('kh/getKhIntent', 'API\KHController@getKhIntent');
    //根据客户id获取客户详细信息
    Route::get('kh/getKHById', 'API\KHController@getKHById');
    Route::post('user/enter', 'API\LoginController@enter');
    //搜索房源
    Route::post('hr/getSearch', 'API\HRController@getSearch');
    //搜索客户
    Route::post('kh/getSearchKh', 'API\KHController@getSearchKh');
    Route::post('hr/getSearchHr', 'API\HRController@getSearchHr');
    Route::get('news/getNEWs', 'API\KHController@getNEWs');
    //获取用户的佣金
    Route::get('user/getUserYongjin', 'API\UserController@getUserYongjin');

    //获取积分商品
    Route::get('goods/getGoodsList', 'API\GoodsController@getGoodsList');       //获取商品兑换列表
    Route::get('goods/getGoodsById', 'API\GoodsController@getGoodsById');     //根据id获取商品明细信息
    Route::post('goods/exchange', 'API\GoodsController@exchange')->middleware('CheckToken');     //兑换商品
    Route::get('goods/getExchangeListByUserId', 'API\GoodsController@getExchangeListByUserId')->middleware('CheckToken');        //根据id获取商品明细信息


    //获取合作细则
    Route::get('user/getHezuo', 'API\UserController@getHezuo');

    Route::get('user/getWhitebook', 'API\UserController@getWhitebook');
    //获取积分规则
    Route::get('rules/getRules', 'API\UserController@getRules');
    //获取客户对我的接待评价
    Route::get('pingjia/getPingjia', 'API\UserController@getPingjia');


});