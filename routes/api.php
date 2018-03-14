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
    Route::get('user/getByIdWithToken', 'API\UserController@getByIdWithToken')->middleware('CheckToken');  //根据id获取用户信息带token
    Route::get('user/getXCXOpenId', 'API\UserController@getXCXOpenId');//根据code获取openid
    Route::get('user/getUnionId', 'API\UserController@getUnionId');    //根据code获取unionId
    Route::post('user/login', 'API\UserController@login');    //登录/注册
    Route::post('user/updateById', 'API\UserController@updateById')->middleware('CheckToken');//更新用户信息
    Route::post('user/encryptedData', 'API\UserController@encryptedData');//解密encryptedData
    Route::post('user/applyUp', 'API\UserUpController@userUpApply');//中介升级为案场负责接口
    Route::get('user/getMyInfo', 'API\UserController@getMyInfo');//获取个人主页的相关数据
    Route::get('userUp/getListByUserId', 'API\UserUpController@getListByUserId')->middleware('CheckToken');    //根据用户id获取申请列表

    //微信相关接口
    Route::get('wechat/miniProgramLogin', 'API\WechatController@miniProgramLogin'); //小程序通过code换取openid接口
    Route::any('wechat', 'API\WechatController@serve');     //微信服务号 加入接口
    Route::get('wechat/testTemplateMessage', 'API\WechatController@testTemplateMessage'); //测试模板消息

    //获取广告图
    Route::get('ad/getADs', 'API\ADController@getADs'); //获取首页轮播图
    Route::get('ad/getADById', 'API\ADController@getADById');   //根据轮播图的id获取相应的信息
//    Route::get('ad/getADById', 'API\ADController@getADById');//根据轮播图的id获取相应的信息

    //用户签到-By TerryQi
    Route::post('user/userQDToday', 'API\UserQDController@userQDToday')->middleware('CheckToken', 'CheckStatus');        //用户签到接口
    Route::get('user/getUserQDsByUserId', 'API\UserQDController@getUserQDsByUserId')->middleware('CheckToken');        //根据用户id获取签到列表
    Route::get('user/getRecentDatas', 'Admin\UserQDController@getRecentDatas');        //获取近几日综合统计数据

    //用户推荐
    Route::post('recomm/recommUser', 'API\RecommController@recommUser')->middleware('CheckToken');        //推荐用户
    Route::get('recomm/getListByReUserId', 'API\RecommController@getListByReUserId')->middleware('CheckToken');        //根据推荐人id获取被推荐人列表


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
    Route::get('house/getHuxings', 'API\HouseController@getHuxingsByHouseId');       //获取楼盘下所有生效的产品（户型）
    Route::get('house/getHouseDetail', 'API\HouseController@getHouseDetailByHouseId');       //获取楼盘下的楼盘参数
    Route::get('house/getZYGWs', 'API\HouseController@getZYGWsByHouseId')->middleware('CheckToken');       //获取楼盘下所有生效的置业顾问

    //报备相关
    Route::get('baobei/getById', 'API\BaobeiController@getById');       //获取报备选项
    Route::get('baobei/getOptions', 'API\BaobeiController@getBaobeiOption');       //获取报备选项
    Route::post('baobei/acceptClient', 'API\BaobeiController@acceptClient')->middleware('CheckToken', 'CheckStatus', 'CheckBaobeiStatus');     //中介/案场负责人报备客户
    Route::post('baobei/setNormalInfo', 'API\BaobeiController@setNormalInfo')->middleware('CheckToken', 'CheckStatus', 'CheckBaobeiStatus');     //中介/案场负责人报备客户
    Route::post('baobei/baobeiClient', 'API\BaobeiController@baobeiClient')->middleware('CheckToken', 'CheckStatus');     //中介/案场负责人报备客户
    Route::post('baobei/daofang', 'API\BaobeiController@daofang')->middleware('CheckToken', 'CheckStatus', 'CheckBaobeiStatus');     //中介/案场负责人设置客户到访
    Route::post('baobei/deal', 'API\BaobeiController@deal')->middleware('CheckToken', 'CheckStatus', 'CheckBaobeiStatus');     //案场负责人报备成交信息
    Route::post('baobei/sign', 'API\BaobeiController@sign')->middleware('CheckToken', 'CheckStatus', 'CheckBaobeiStatus');     //案场负责人报备签约信息
    Route::post('baobei/qkdz', 'API\BaobeiController@qkdz')->middleware('CheckToken', 'CheckStatus', 'CheckBaobeiStatus');     //案场负责人报备全款到账信息
    Route::post('baobei/setZYGW', 'API\BaobeiController@setZYGW')->middleware('CheckToken', 'CheckStatus', 'CheckBaobeiStatus');     //设置报备记录的置业顾问
    Route::post('baobei/canjiesuan', 'API\BaobeiController@canjiesuan')->middleware('CheckToken', 'CheckStatus', 'CheckBaobeiStatus');     //案场负责人设置报备单可结算
    Route::get('baobei/getListForZJByStatus', 'API\BaobeiController@getListForZJByStatus')->middleware('CheckToken');   //获取中介维度的报备列表
    Route::get('baobei/getListForACByStatus', 'API\BaobeiController@getListForACByStatus')->middleware('CheckToken');   //获取案场负责人维度的报备列表
    Route::get('baobei/getWaitingForAcceptListByAnchangId', 'API\BaobeiController@getWaitingForAcceptListByAnchangId')->middleware('CheckToken');   //根据案场负责人id获取其楼盘下未接收的报备列表

    //文章管理
    Route::get('tw/getInfoById', 'API\TWController@getInfoById');//图文
    Route::get('tw/getTWByType', 'API\TWController@getByType');//根据图文类型获取相关图文

    //手动执行计划任务
    Route::get('schedule/execBaobeiExceedSchedule', 'API\BaobeiController@execBaobeiExceedSchedule');   //执行报备超期计划任务
    Route::get('schedule/execDealExceedSchedule', 'API\BaobeiController@execDealExceedSchedule');   //执行到访超期计划任务
});