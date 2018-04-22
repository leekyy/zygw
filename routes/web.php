<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//登录

Route::get('/admin/login', 'Admin\LoginController@login');        //登录
Route::post('/admin/login', 'Admin\LoginController@loginPost');   //post登录请求


Route::group(['prefix' => 'admin', 'middleware' => ['admin.login']], function () {

    //首页
    Route::get('/', 'Admin\IndexController@index');       //首页
    Route::get('/index', 'Admin\IndexController@index');  //首页
    Route::get('/dashboard/index', 'Admin\IndexController@index');    //首页
    Route::get('/dashboard/export', 'Admin\IndexController@export');  //导出数据

    //错误页面
    Route::get('/error/500', 'Admin\IndexController@error');  //错误页面

    //轮播管理
    Route::get('/ad/index', 'Admin\ADController@index');  //轮播管理首页
    Route::get('/ad/setStatus/{id}', 'Admin\ADController@setStatus');  //设置轮播状态
    Route::get('/ad/del/{id}', 'Admin\ADController@del');  //删除轮播
    Route::get('/ad/edit', 'Admin\ADController@edit');  //新建或编辑轮播
    Route::post('/ad/edit', 'Admin\ADController@editPost');  //新建或编辑轮播
    Route::get('/ad/editAD', 'Admin\ADController@editAD');  //编辑文章页面
    Route::post('/ad/editAD', 'Admin\ADController@editADPost'); //编辑文章Post

    //管理员管理
    Route::get('/admin/index', 'Admin\AdminController@index');  //管理员管理首页
    Route::get('/admin/del/{id}', 'Admin\AdminController@del');  //删除管理员
    Route::get('/admin/edit', 'Admin\AdminController@edit');  //新建或编辑管理员
    Route::post('/admin/edit', 'Admin\AdminController@editPost');  //新建或编辑管理员
    Route::get('/admin/getById', 'Admin\AdminController@getById');  //根据id获取管理员信息
    Route::get('/admin/editInfo', 'Admin\AdminController@editInfo');    //修改信息页面
    Route::get('/admin/changePassword', 'Admin\AdminController@changePassword');    //修改密码页面
    Route::post('/admin/changePassword', 'Admin\AdminController@changePasswordPost');    //修改密码页面
    Route::get('/admin/logout', 'Admin\LoginController@loginout');  //注销
    Route::get('/admin/resetPassword', 'Admin\AdminController@resetPassword');    //重置管理员密码

    //中介申请成为案场负责人管理
    Route::get('/userUp/index', 'Admin\UserUpController@index');  //管理员管理首页
    Route::get('/userUp/setStatus/{id}', 'Admin\UserUpController@setStatus');  //设置升级状态
    Route::post('/userUp/search', 'Admin\UserUpController@search');  //搜索申请记录

    //案场负责人
    Route::get('/acfzr/index', 'Admin\UserACFZRController@index');  //案场负责人管理首页
    Route::post('/acfzr/search', 'Admin\UserACFZRController@search');  //搜索案场负责人
    Route::get('/acfzr/setStatus/{id}', 'Admin\UserACFZRController@setStatus');  //设置状态
    Route::get('/acfzr/stmt', 'Admin\UserACFZRController@stmt');  //案场负责人统计页面

    //中介
    Route::get('/zhongjie/index', 'Admin\UserZJController@index');  //中介管理首页
    Route::get('/zhongjie/search', 'Admin\UserZJController@search');  //搜索中介
    Route::get('/zhongjie/setStatus/{id}', 'Admin\UserZJController@setStatus');  //设置状态
    Route::get('/zhongjie/stmt', 'Admin\UserZJController@stmt');  //中介统计页面
    Route::get('/zhongjie/paiming', 'Admin\UserZJController@paiming');  //中介排名页面
    Route::post('/zhongjie/payYongjin', 'Admin\UserZJController@payYongjin');  //支付中介佣金

    //报备
    Route::get('/baobei/info', 'Admin\BaobeiController@info');  //报备详情
    Route::get('/baobei/resetDealInfo', 'Admin\BaobeiController@resetDealInfo');  //重新设置交易详情-get
    Route::post('/baobei/resetDealInfo', 'Admin\BaobeiController@resetDealInfoPost');  //重新设置交易详情-post

    //楼盘管理
    Route::get('/house/index', 'Admin\HouseController@index');//楼盘管理首页
    Route::get('/house/setStatus/{id}', 'Admin\HouseController@setStatus');  //设置楼盘状态
    Route::get('/house/del/{id}', 'Admin\HouseController@del');//删除楼盘
    Route::post('/house/edit', 'Admin\HouseController@edit');//新建或编辑楼盘
    Route::post('/house/edit', 'Admin\HouseController@editPost');//新建或编辑楼盘
    Route::get('/house/getById', 'Admin\HouseController@getById');//根据id获取楼盘信息
    Route::post('/house/search', 'Admin\HouseController@search');  //设置楼盘状态
    Route::get('/house/stmt', 'Admin\HouseController@stmt');  //系统配置首页信息
    Route::get('/house/getRecentDatas', 'Admin\HouseController@getRecentDatas');        //获取近几日综合统计数据

    //楼盘详细信息
    Route::get('/house/detail', 'Admin\HouseController@detail');//根据id获取楼盘详细信息
    Route::get('/house/del/{id}', 'Admin\HouseController@delHouseDetail');//删除楼盘
    Route::get('/house/getHouseDetailById', 'Admin\HouseController@getHouseDetailById');//根据id获取产品信息
    Route::post('/detail/edit', 'Admin\HouseController@editHouseDetail');//新建或编辑楼盘
    Route::post('/detail/edit', 'Admin\HouseController@editPostHouseDetail');//新建或编辑楼盘

    //楼盘下的产品管理
    Route::get('/huxing/index', 'Admin\HuxingController@index');//根据楼盘id获取相应的产品
    Route::get('/huxing/del/{id}', 'Admin\HuxingController@del');//删除产品
    Route::post('/huxing/edit', 'Admin\HuxingController@edit');//新建或编辑楼盘
    Route::post('/huxing/edit', 'Admin\HuxingController@editPost');//新建或编辑楼盘
    Route::get('/huxing/getById', 'Admin\HuxingController@getById');//根据id获取产品信息
    Route::get('/huxing/setStatus/{id}', 'Admin\HuxingController@setStatus');  //设置产品状态
    Route::post('/huxing/editYongjin', 'Admin\HuxingController@editYongjin');//新建或编辑产品的佣金

    //产品下的户型样式
    Route::get('/huxingStyle/index', 'Admin\HuxingStyleController@index');//根据户型id获取户型样式
    Route::post('/huxingStyle/edit', 'Admin\HuxingStyleController@edit');//新建或编辑户型样式
    Route::post('/huxingStyle/edit', 'Admin\HuxingStyleController@editPost');//新建或编辑户型样式
    Route::get('/huxingStyle/getById', 'Admin\HuxingStyleController@getById');//根据id获取户型样式信息
    Route::get('/huxingStyle/setStatus/{id}', 'Admin\HuxingStyleController@setStatus');  //设置户型样式状态
    Route::get('/huxingStyle/del/{id}', 'Admin\HuxingStyleController@del');//删除户型样式


    //产品佣金设置记录
    Route::get('/huxingYongjinRecord/index', 'Admin\HuxingYongjinRecordController@index');//查看佣金记录

    //楼盘下的顾问管理
    Route::get('/zygw/index', 'Admin\ZYGWController@index');//根据楼盘id获取相应的顾问
    Route::get('/zygw/del/{id}', 'Admin\ZYGWController@del');//删除顾问
    Route::get('/zygw/edit', 'Admin\ZYGWController@edit');//新建或编辑楼盘
    Route::post('/zygw/edit', 'Admin\ZYGWController@editPost');//新建或编辑楼盘
    Route::get('/zygw/getById', 'Admin\ZYGWController@getById');//根据id获取楼盘信息
    Route::get('/zygw/setStatus/{id}', 'Admin\ZYGWController@setStatus');  //设置顾问状态

    //楼盘下的房地产客户管理
    Route::get('/houseClient/index', 'Admin\HouseClientController@index');//根据楼盘id获取相应的房地产客户
    Route::post('/houseClient/edit', 'Admin\HouseClientController@editPost');//新建或编辑房地产客户
    Route::get('/houseClient/del/{id}', 'Admin\HouseClientController@del');//删除房地产客户

    //报备详情页面
    Route::get('/baobei/info', 'Admin\BaobeiController@info');  //报备详情页
    Route::get('/baobei/index', 'Admin\BaobeiController@index');  //报备管理首页

    //客户管理
    Route::get('/client/index', 'Admin\ClientController@index');  //客户管理首页
    Route::get('/client/stmt', 'Admin\ClientController@stmt');  //客户统计
    Route::post('/client/search', 'Admin\ClientController@search');  //客户搜索首页

    //产品标签
    Route::get('/houseLabel/index', 'Admin\HouseLabelController@index');  //楼盘标签管理首页
    Route::post('/houseLabel/edit', 'Admin\HouseLabelController@editPost');  //楼盘编辑产品标签
    Route::get('/houseLabel/getById', 'Admin\HouseLabelController@getById');  //根据id获取商品信息
    Route::get('/houseLabel/del/{id}', 'Admin\HouseLabelController@del');//删除楼盘

    //产品类型
    Route::get('/houseType/index', 'Admin\HouseTypeController@index');  //楼盘类型管理首页
    Route::post('/houseType/edit', 'Admin\HouseTypeController@editPost');  //编辑楼盘标签
    Route::get('/houseType/getById', 'Admin\HouseTypeController@getById');  //根据id获取商品信息
    Route::get('/houseType/del/{id}', 'Admin\HouseTypeController@del');//删除楼盘

    //系统配置信息相关
    Route::get('/system/index', 'Admin\SystemController@index');  //系统配置首页信息
    Route::get('/system/edit', 'Admin\SystemController@edit');  //设置系统页面
    Route::post('/system/edit', 'Admin\SystemController@editPost');  //设置系统页面-post

    //签到相关
    Route::get('/userQD/index', 'Admin\UserQDController@index');  //系统配置首页信息
    Route::get('/userQD/stmt', 'Admin\UserQDController@stmt');  //系统配置首页信息
    Route::post('/userQD/search', 'Admin\UserQDController@search');  //系统配置首页信息
    Route::get('/userQD/getRecentDatas', 'Admin\UserQDController@getRecentDatas');        //获取近几日综合统计数据

    //商品管理
    Route::get('/goods/index', 'Admin\GoodsController@index');  //商品管理首页
    Route::get('/goods/setStatus/{id}', 'Admin\GoodsController@setStatus');  //设置商品状态
    Route::get('/goods/del/{id}', 'Admin\GoodsController@del');  //删除商品
    Route::get('/goods/getById', 'Admin\GoodsController@getById');  //根据id获取商品信息
    Route::post('/goods/edit', 'Admin\GoodsController@editPost');  //新建或编辑商品

    //兑换订单管理
    Route::get('/goodsexchange/index', 'Admin\GoodsExchangeController@index');//订单管理首页
    Route::post('/goodsexchange/setStatus', 'Admin\GoodsExchangeController@setStatus');  //设置订单状态
    Route::get('/goodsexchange/del/{id}', 'Admin\GoodsExchangeController@del');  //删除订单
    Route::get('/goodsexchange/stmt', 'Admin\GoodsExchangeController@stmt');  //系统配置首页信息
    Route::get('/goodsexchange/getById', 'Admin\GoodsExchangeController@getById');//根据id获取订单信息
    Route::get('/goodsexchange/getRecentDatas', 'Admin\GoodsExchangeController@getRecentDatas');        //获取近几日综合统计数据
    Route::get('/goodsexchange/edit', 'Admin\GoodsExchangeController@edit');  //新建或编辑管理员
    Route::post('/goodsexchange/edit', 'Admin\GoodsExchangeController@editPost');  //新建或编辑商品


    //文章管理
    Route::get('/tw/index', 'Admin\TWController@index');  //文章管理首页
    Route::get('/tw/setStatus/{id}', 'Admin\TWController@setStatus');  //设置文章状态
    Route::get('/tw/del/{id}', 'Admin\TWController@del');  //删除文章
    Route::get('/tw/editTW', 'Admin\TWController@editTW');  //编辑文章页面
    Route::post('/tw/editTW', 'Admin\TWController@editTWPost'); //编辑文章Post


});