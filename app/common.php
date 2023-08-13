<?php
// 应用公共文件

// 如何函数不存在
if (!function_exists('encrypt_password')) {
	// 密码加密函数
	function encrypt_password($password)
	{
		$salt = 'suibian';
		return md5(md5($salt) . $password);
	}
}