<?php
declare (strict_types=1);

namespace app\adminApi\controller;

use app\common\model\Admin;
use fixed\captcha\Captcha;
use think\facade\Cache;
use tools\jwt\Token;

class Login extends BaseApi
{
	/**
	 * 验证码接口
	 */
	public function captcha()
	{
		$captcha = new Captcha();
		$result = $captcha->create();
		
		//返回数据 验证码图片路径、验证码标识
		$data = ['src' => $result['content'], 'uniqid' => $result['uniqid']];
		$this->ok($data);
	}
	
	/**
	 * 登录接口
	 */
	public function login()
	{
		// 接受参数
		$params = input();
		// 参数检测（表单验证）
		$validate = $this->validate($params, [
			'username|用户名' => 'require',
			'password|密码' => 'require',
			'code|验证码' => 'require',
			// 'code|验证码' => 'require|captcha:'.$params['uniqid'], //验证码自动校验
			'uniqid|验证码标识' => 'require',
		]);
		if ($validate !== true) {
			// 参数验证失败
			$this->fail($validate, 401);
		}
		
		// 校验验证码-手动校验
		$captcha = new Captcha();
		// 拿传入参数uniqid去缓存中取
		$uniqid = $params['uniqid'];
		$code = $params['code'];
		if (!$captcha->check($uniqid, $code)) {
			// 验证码错误
			$this->fail('验证码错误', 402);
		}
		
		// 查询用户表进行验证
		$password = encrypt_password($params['password']);
		$info = Admin::where('username', $params['username'])
			->where('password', $password)
			->find();
		if (empty($info)) {
			// 用户名或者密码错误
			$this->fail('用户名或者密码错误', 403);
		}
		
		// 生成token令牌
		$token = Token::getToken($info['id']);
		
		// 返回数据
		$data = [
			'token' => $token,
			'user_id' => $info['id'],
			'username' => $info['username'],
			'nickname' => $info['nickname'],
			'email' => $info['email'],
		];
		$this->ok($data);
	}
	
	/**
	 * 后台退出接口
	 */
	public function logout()
	{
		// 从logout请求头中 取出token值
		$delete_token = Token::getRequestToken();
		// 传给logout方法
		Token::logout($delete_token);
		// 返回数据
		$this->ok();
	}
}
