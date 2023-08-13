<?php
declare (strict_types=1);

namespace app\adminApi\controller;

use think\facade\Db;
use  \tools\jwt\Token;

class Index extends BaseApi
{
	public function index()
	{
		// 测试通用响应
		// $this->response();
		// $this->response(200, 'success', ['id' => 100, 'name' => '张三']);
		// $this->response(400, '参数错误');
		
		// 测试成功响应
		$this->ok(['id' => 100, 'name' => '张三']);
		
		// 测试失败响应
		$this->fail('参数错误');
		$this->fail('参数错误', 401);
		dump('233');
		
		// 测试token工具类
		
		// 生成token
		$token = Token::getToken(['id' => 100, 'name' => '张三']);
		echo $token;
		// 解析token 得到用户ID
		$user_id = Token::getParse($token);
		dump($user_id);
		
		// 测试加密
		echo encrypt_password('123456');
		
		// 测试数据库的配置
		// $goods = Db::table('pyg_goods')->find(1);
		// dump($goods);
	}
}
