<?php

use think\facade\Route;

// 后台接口的域名路由 adminApi
Route::domain('adminapi.pyg.com', function () {
	// 获取验证码接口
	Route::get('captcha', 'adminApi/login/captcha');
	//登录接口
	Route::post('login', 'adminApi/login/login');
	// 退出接口
	Route::get('logout', 'adminApi/login/logout');
});