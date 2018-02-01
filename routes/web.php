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
Route::get('/admin/loginout', 'Admin\LoginController@loginout');  //注销

Route::group(['prefix' => 'admin', 'middleware' => ['admin.login']], function () {

    //首页
    Route::get('/', 'Admin\IndexController@index');       //首页
    Route::get('/index', 'Admin\IndexController@index');  //首页
    Route::get('/dashboard/index', 'Admin\IndexController@index');    //首页

    //错误页面
    Route::get('/error/500', 'Admin\IndexController@error');  //错误页面

    //轮播管理
    Route::get('/ad/index', 'Admin\ADController@index');  //轮播管理首页
    Route::get('/ad/setStatus/{id}', 'Admin\ADController@setStatus');  //设置轮播状态
    Route::get('/ad/del/{id}', 'Admin\ADController@del');  //删除轮播
    Route::get('/ad/edit', 'Admin\ADController@edit');  //新建或编辑轮播
    Route::post('/ad/edit', 'Admin\ADController@editPost');  //新建或编辑轮播

    //管理员管理
    Route::get('/admin/index', 'Admin\AdminController@index');  //管理员管理首页
    Route::get('/admin/del/{id}', 'Admin\AdminController@del');  //删除管理员
    Route::get('/admin/edit', 'Admin\AdminController@edit');  //新建或编辑管理员
    Route::post('/admin/edit', 'Admin\AdminController@editPost');  //新建或编辑管理员
    Route::get('/admin/getById', 'Admin\AdminController@getById');  //根据id获取管理员信息

    //中介申请成为案场负责人管理
    Route::get('/userUp/index', 'Admin\UserUpController@index');  //管理员管理首页
    Route::get('/userUp/setStatus/{id}', 'Admin\UserUpController@setStatus');  //设置升级状态
    Route::post('/userUp/search', 'Admin\UserUpController@search');  //搜索申请记录

    //案场负责人
    Route::get('/acfzr/index', 'Admin\UserACFZRController@index');  //案场负责人管理首页
    Route::post('/acfzr/search', 'Admin\UserACFZRController@search');  //搜索案场负责人
    Route::get('/acfzr/setStatus/{id}', 'Admin\UserACFZRController@setStatus');  //设置状态

    //中介
    Route::get('/zhongjie/index', 'Admin\UserZJController@index');  //中介管理首页
    Route::get('/zhongjie/search', 'Admin\UserZJController@search');  //搜索中介
    Route::get('/zhongjie/setStatus/{id}', 'Admin\UserZJController@setStatus');  //设置状态

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
    Route::get('/detail/del/{id}', 'Admin\HouseController@del');//删除楼盘
    Route::post('/detail/edit', 'Admin\HouseController@edit');//新建或编辑楼盘
    Route::post('/detail/edit', 'Admin\HouseController@editPost');//新建或编辑楼盘


    //楼盘下的产品管理
    Route::get('/huxing/index', 'Admin\HuxingController@index');//根据楼盘id获取相应的产品
    Route::get('/huxing/del/{id}', 'Admin\HuxingController@del');//删除产品
    Route::post('/huxing/edit', 'Admin\HuxingController@edit');//新建或编辑楼盘
    Route::post('/huxing/edit', 'Admin\HuxingController@editPost');//新建或编辑楼盘
    Route::get('/huxing/getById', 'Admin\HuxingController@getById');//根据id获取产品信息
    Route::get('/huxing/setStatus/{id}', 'Admin\HuxingController@setStatus');  //设置产品状态
    Route::post('/huxing/editYongjin', 'Admin\HuxingController@editYongjin');//新建或编辑产品的佣金

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

    //客户管理
    Route::get('/kehu/index', 'Admin\KeHuController@index');  //客户管理首页
    Route::get('/kehu/del/{id}', 'Admin\KeHuController@del');  //删除客户
    Route::get('/kehu/edit', 'Admin\KeHuController@edit');  //新建或编辑客户
    Route::post('/kehu/edit', 'Admin\KeHuController@editPost');  //新建或编辑客户
    Route::get('/kehu/getById', 'Admin\KeHuController@getById');  //根据id获取客户信息

    //产品标签
    Route::get('/houseLabel/index', 'Admin\HouseLabelController@index');  //楼盘标签管理首页
    Route::post('/houseLabel/edit', 'Admin\HouseLabelController@editPost');  //楼盘编辑产品标签

    //产品类型
    Route::get('/houseType/index', 'Admin\HouseTypeController@index');  //楼盘类型管理首页
    Route::post('/houseType/edit', 'Admin\HouseTypeController@editPost');  //编辑楼盘标签


    //系统配置信息相关
    Route::get('/system/index', 'Admin\SystemController@index');  //系统配置首页信息
    Route::get('/system/edit', 'Admin\SystemController@edit');  //设置系统页面
    Route::post('/system/edit', 'Admin\SystemController@editPost');  //设置系统页面-post

    //签到相关
    Route::get('/userQD/index', 'Admin\UserQDController@index');  //系统配置首页信息
    Route::get('/userQD/stmt', 'Admin\UserQDController@stmt');  //系统配置首页信息
    Route::post('/userQD/search', 'Admin\UserQDController@search');  //系统配置首页信息
    Route::get('/userQD/getRecentDatas', 'Admin\UserQDController@getRecentDatas');        //获取近几日综合统计数据

    //换商品管理
    Route::get('/goods/index', 'Admin\GoodsController@index');  //商品管理首页
    Route::get('/goods/setStatus/{id}', 'Admin\GoodsController@setStatus');  //设置商品状态
    Route::get('/goods/del/{id}', 'Admin\GoodsController@del');  //删除商品
    Route::get('/goods/getById', 'Admin\GoodsController@getById');  //根据id获取商品信息
    Route::post('/goods/edit', 'Admin\GoodsController@editPost');  //新建或编辑商品

    //兑换订单管理
    Route::get('/goodsexchange/index', 'Admin\GoodsExchangeController@index');//订单管理首页
    Route::get('/goodsexchange/setStatus/{id}', 'Admin\GoodsExchangeController@setStatus');  //设置订单状态
    Route::get('/goodsexchange/del/{id}', 'Admin\GoodsExchangeController@del');  //删除订单
    Route::get('/goodsexchange/stmt', 'Admin\GoodsExchangeController@stmt');  //系统配置首页信息
    Route::get('/goodsexchange/getById', 'Admin\GoodsExchangeController@getById');//根据id获取订单信息
    Route::get('/goodsexchange/getRecentDatas', 'Admin\GoodsExchangeController@getRecentDatas');        //获取近几日综合统计数据
    Route::get('/goodsexchange/edit', 'Admin\GoodsExchangeController@edit');  //新建或编辑管理员
    Route::post('/goodsexchange/edit', 'Admin\GoodsExchangeController@editPost');  //新建或编辑商品
    Route::get('/goodsexchange/rule', 'Admin\RuleController@index');//积分兑换规则首页
    Route::get('/rule/setStatus/{id}', 'Admin\RuleController@setStatus');  //设置规则状态
    Route::get('/rule/del/{id}', 'Admin\RuleController@del');  //删除订单
    Route::get('/rule/getById', 'Admin\RuleController@getById');//根据id获取积分兑换规则信息
    Route::get('/rule/edit', 'Admin\RuleController@edit');  //新建或编辑规则
    Route::post('/rule/edit', 'Admin\RuleController@editPost');  //新建或编辑规则


    //合作细则管理
    Route::get('/hezuo/index', 'Admin\HeZuoController@index');  //合作细则管理首页
    Route::get('/hezuo/del/{id}', 'Admin\HeZuoController@del');  //删除合作细则
    Route::get('/hezuo/setStatus/{id}', 'Admin\HeZuoController@setStatus');  //设置合作细则状态
    Route::get('/hezuo/edit', 'Admin\HeZuoController@edit');  //新建或编辑合作细则
    Route::post('/hezuo/edit', 'Admin\HeZuoController@editPost');  //新建或编辑合作细则
    Route::get('/hezuo/getById', 'Admin\HeZuoController@getById');  //根据id获取合作细则信息

    //行业白皮书管理
    Route::get('/whitebook/index', 'Admin\WhiteBookController@index');  //行业白皮书管理首页
    Route::get('/whitebook/del/{id}', 'Admin\WhiteBookController@del');  //删除行业白皮书
    Route::get('/whitebook/setStatus/{id}', 'Admin\WhiteBookController@setStatus');  //设置行业白皮书状态
    Route::get('/whitebook/edit', 'Admin\WhiteBookController@edit');  //新建或编辑行业白皮书
    Route::post('/whitebook/edit', 'Admin\WhiteBookController@editPost');  //新建或编辑行业白皮书
    Route::get('/whitebook/getById', 'Admin\WhiteBookController@getById');  //根据id获取行业白皮书信息

});