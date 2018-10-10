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

/* 增删改查Cms */
Route::get('/mycms' , 'Cms@test');

Route::get('/hello' , 'Cms@index');

Route::get('/userslist' , 'Cms@userlist');
Route::get('/usersadd', 'Cms@useradd');
Route::post('/usersadd_do', 'Cms@useradd_do');
Route::get('/deluser' , 'Cms@userdel');
Route::get('/modifyuser' , 'Cms@updateuser');
Route::post('/usersmodify_do' , 'Cms@usermodify_do');

/* 后台登陆 */
Route::get('/home/login' , 'Home\LoginController@login');
Route::post('/home/login_do' , 'Home\LoginController@login_do');

/* 申请账号 */
Route::get('/home/register' , "Home\LoginController@register");

/* 后台首页 */
Route::get('/home/index' , 'Home\IndexController@index');
Route::get('/home/welcome' , 'Home\IndexController@welcome');

/* 后台权限管理 */
Route::get('/home/admin-rule' , 'Home\RuleController@list');
Route::get('/home/rule-add' , 'Home\RuleController@add');
Route::post('/home/rule-add-do' , 'Home\RuleController@add_do');


/* 后台类别管理 */
Route::get('/home/type-add' , 'Home\TypeController@typeadd');
Route::post('/home/type-add-do' , 'Home\TypeController@typeadd_do');
Route::get('/home/type-list' , 'Home\TypeController@typelist');

/* 后台商品管理 */
Route::get('goodsadd' , 'Home\GoodsController@goods_add');
Route::any('goodsadd_do' , 'Home\GoodsController@goods_add_do');

/* banner图管理--轮播图 */
Route::get('banneradd' , 'Home\BannerController@banner_add');
Route::post('banneradd_do' , 'Home\BannerController@banner_add_do');
Route::get('bannerlist' , 'Home\BannerController@banner_list');


/* 后台统计日手机订单和电脑订单 */
Route::get('statistics' , 'Home\IndexController@statistics');

/* 后台分析nginx访问日志 */
Route::get('log' , 'Home\IndexController@log');

/*  微站Phone\UserController */
//首页
Route::get('/' , 'Phone\IndexController@index');
Route::get('/detail' , 'Phone\IndexController@detail');
Route::post('/create_cart' , 'Phone\IndexController@create_cart');
//购物车
Route::get('/shopcar' , 'Phone\ShopcarController@shopcar');
Route::post('/buy' , 'Phone\ShopcarController@buy');
Route::post('order_status' , 'Phone\ShopcarController@order_status');
Route::get('/buy_view' , 'Phone\ShopcarController@buy_view');
Route::post('up_num' , 'Phone\ShopcarController@up_num');

//订单--支付
Route::get('/alipay/go_pay' , 'Phone\PayController@go_pay');
Route::any('/alipay/notify_url' , 'Phone\PayController@notify_url');
Route::get('/alipay/return_url' , 'Phone\PayController@return_url');
Route::get('/testpay' , 'Phone\ShopcarController@test_pay');
Route::get('recovery' , 'Phone\ShopcarController@recovery');

//退款
Route::get('alipay/refund' , 'Phone\PayController@refund');

//用户中心
Route::get('self' , 'Phone\UserController@self');

//收货地址管理
Route::get('addressadd' , 'Phone\AddressController@add');
Route::get('address' , 'Phone\AddressController@index');
Route::get('addressdetail' , 'Phone\AddressController@detail');
Route::get('addressedit' , 'Phone\AddressController@edit');
Route::post('get_city' , 'Phone\AddressController@city');

//订单列表
Route::get('order' , 'Phone\UserController@order');
//登录
Route::get('login' , 'Phone\UserController@login');
Route::post('login_do' , 'Phone\UserController@login_do');
Route::get('wx_login' , 'Phone\UserController@wx_login');
Route::get('is_band' , 'Phone\UserController@is_band');
Route::get('kip' , 'Phone\UserController@kip');
Route::post('band_do' , 'Phone\UserController@band_do');
Route::get('sm-login' , 'Phone\UserController@sm_login');
Route::post('is-login' , 'Phone\UserController@is_login');
Route::get('sm-login-do' , 'Phone\UserController@sm_login_do');
Route::get('get-openid' , 'Phone\UserController@get_openid');
Route::get('login-view' , 'Phone\UserController@login_view');
Route::get('cancel' , 'Phone\UserController@cancel');
//Route::post('');
//重置密码
Route::get('reset_password' , 'Phone\UserController@reset_password');
Route::post('reset_do' , 'Phone\UserController@reset_do');
//退出
Route::get('logout' , 'Phone\UserController@logout');
//注册
Route::get('register' , 'Phone\UserController@register');
Route::post('register_do' , 'Phone\UserController@register_do');
//获取验证码(短信)
Route::post('get_sms' , 'Phone\UserController@get_sms');
//个人详情
Route::get('personal' , 'Phone\UserController@personal');
//分类
Route::get('assort' , 'Phone\AssortController@assort');


//测试-----打卡
Route::get('sign' , 'Phone\SignController@index');
Route::any('sign_do' , 'Phone\SignController@sign');

//微信
Route::get('getAccessToken' , "Phone\WechatController@getAccessToken");
Route::get('qrcode' , 'Phone\WechatController@qrcode');
Route::get('getcode' , 'Phone\UserController@get');


//分销
Route::get('distributor-info' , "Phone\DistributorController@distributor");
Route::get('distributor-test' , "Phone\DistributorController@test");


//微相册
Route::get('set-redis' , "Phone\AlbumController@set_redis");
Route::get('album-list' , "Phone\AlbumController@album_list");
Route::post('create-album' , "Phone\AlbumController@create_album");
Route::post('up-name' , "Phone\AlbumController@up_name");
Route::get('photo-list' , "Phone\AlbumController@album_view");
Route::any('get-photo' , "Phone\AlbumController@get_photo");

//购物
Route::get('scan-goods' , "Phone\WxpayController@scan_goods");
Route::get('goods-qrcode' , "Phone\WxpayController@goods_qrcode");
Route::get('wx-buy' , 'Phone\WxpayController@wx_buy');
