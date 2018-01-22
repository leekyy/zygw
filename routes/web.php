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
    Route::get('/userUp/setStatus/{id}', 'Admin\UserUpController@setStatus');  //设置轮播状态
    Route::post('/userUp/search', 'Admin\UserUpController@search');  //设置轮播状态

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
});