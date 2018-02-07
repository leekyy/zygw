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
    //用户相关
    Route::get('user/getById', 'API\UserController@getUserById');    //根据id获取用户信息
    Route::get('user/getByIdWithToken', 'API\UserController@getUserInfoByIdWithToken')->middleware('CheckToken');  //根据id获取用户信息带token
    Route::get('user/getXCXOpenId', 'API\UserController@getXCXOpenId');//根据code获取openid
    Route::post('user/login', 'API\UserController@login');    //登录/注册
    Route::post('user/updateById', 'API\UserController@updateUserById')->middleware('CheckToken');//更新用户信息
    Route::post('user/encryptedData', 'API\UserController@encryptedData');//解密encryptedData
    Route::post('user/applyUp', 'API\UserUpController@userUpApply');//中介升级为案场负责接口
    Route::get('user/getUserUpHousesByUserId', 'API\UserUpController@getUserUpHousesByUserId');    //获取案场负责人的楼盘
    Route::get('user/yongjinSummary', 'API\UserController@yongjinSummary');    //获取佣金概要
    //微信相关接口
    Route::get('wechat/miniProgramLogin', 'API\WechatController@miniProgramLogin'); //小程序通过code换取openid接口
    Route::any('wechat', 'API\WechatController@serve');     //微信服务号 加入接口
    //获取广告图
    Route::get('ad/getADs', 'API\ADController@getADs'); //获取首页轮播图
    Route::get('ad/getById', 'API\ADController@getADById');   //根据轮播图的id获取相应的信息
    //用户签到-By TerryQi
    Route::post('user/userQDToday', 'API\UserQDController@userQDToday')->middleware('CheckToken');        //用户签到接口
    Route::get('user/getUserQDsByUserId', 'API\UserQDController@getUserQDsByUserId')->middleware('CheckToken');        //根据用户id获取签到列表
    Route::get('user/getRecentDatas', 'Admin\UserQDController@getRecentDatas');        //获取近几日综合统计数据
    //获取积分商品
    Route::get('goods/getGoodsList', 'API\GoodsController@getGoodsList');       //获取商品兑换列表
    Route::get('goods/getGoodsById', 'API\GoodsController@getGoodsById');     //根据id获取商品明细信息
    Route::post('goods/exchange', 'API\GoodsController@exchange')->middleware('CheckToken');     //兑换商品
    Route::get('goods/getExchangeListByUserId', 'API\GoodsController@getExchangeListByUserId')->middleware('CheckToken');        //根据id获取商品明细信息

    //楼盘相关
    Route::get('house/getById', 'API\HouseController@getById');       //根据id获取楼盘信息
    Route::get('house/getOptions', 'API\HouseController@getOptions');       //获取楼盘相关选项
    Route::post('house/searchByName', 'API\HouseController@searchByName');       //根据名称获取楼盘列表
    Route::post('house/searchByCon', 'API\HouseController@searchByCon');       //根据名称获取楼盘列表
    Route::get('house/getHuxings', 'API\HouseController@getHuxingsByHouseId')->middleware('CheckToken');       //获取楼盘下所有生效的产品（户型）
    Route::get('house/getZYGWs', 'API\HouseController@getZYGWsByHouseId')->middleware('CheckToken');       //获取楼盘下所有生效的置业顾问

    //报备相关
    Route::get('baobei/getOptions', 'API\BaobeiController@getBaobeiOption');       //获取报备选项
    Route::post('baobei/acceptClient', 'API\BaobeiController@acceptClient')->middleware('CheckToken');     //中介/案场负责人报备客户
    Route::post('baobei/setNormalInfo', 'API\BaobeiController@setNormalInfo')->middleware('CheckToken');     //中介/案场负责人报备客户
    Route::post('baobei/baobeiClient', 'API\BaobeiController@baobeiClient')->middleware('CheckToken');     //中介/案场负责人报备客户
    Route::post('baobei/daofang', 'API\BaobeiController@daofang')->middleware('CheckToken');     //中介/案场负责人报备客户
    Route::post('baobei/deal', 'API\BaobeiController@deal')->middleware('CheckToken');     //案场负责人报备成交信息
    Route::post('baobei/sign', 'API\BaobeiController@sign')->middleware('CheckToken');     //案场负责人报备签约信息
    Route::post('baobei/qkdz', 'API\BaobeiController@qkdz')->middleware('CheckToken');     //案场负责人报备全款到账信息
    Route::post('baobei/canjiesuan', 'API\BaobeiController@canjiesuan')->middleware('CheckToken');     //案场负责人设置报备单可结算
    Route::get('baobei/getListForZJByStatus', 'API\BaobeiController@getListForZJByStatus')->middleware('CheckToken');   //获取中介维度的报备列表
    Route::get('baobei/getListForACByStatus', 'API\BaobeiController@getListForACByStatus')->middleware('CheckToken');   //获取案场负责人维度的报备列表
});