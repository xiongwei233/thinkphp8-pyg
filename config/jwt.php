<?php
return [
	//注销token缓存key
	'delete_key' => 'delete_token',
	//时区
	'timezone' => 'Asia/Shanghai',
	//编号
	'jti' => '4f1g23a12aa',
	//签名密钥
	'sign' => 'pinyougou',
	//签发人
	'iss' => 'http://adminapi.pyg.com',
	//接收人
	'aud' => [
		'http://www.pyg.com',
		'http://adminapi.pyg.com'
	],
	//主题
	'sub' => '100',
	//有效期(默认24个小时)  单位:秒
	'exp' => 3600 * 24
];