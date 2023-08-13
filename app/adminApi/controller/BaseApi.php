<?php
declare (strict_types=1);

namespace app\adminApi\controller;

use app\BaseController;
use tools\jwt\Token;

class BaseApi extends BaseController
{
	// 无需登录请求前端就能请求的数组
	protected array $no_login = ['login/captcha', 'login/logout'];
	
	// 处理跨域请求
	// 控制器的初始化方法（和直接写构造方法 二选一）
	protected function initialize()
	{
		// 执行父类的initialize方法，
		parent::initialize();
		// 初始化代码: 处理跨域请求
		
		//允许的源域名
		header("Access-Control-Allow-Origin: *");
		//允许的请求头信息
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		//允许的请求类型
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
		
		
		// 登录检测
		// 获取当前请求的控制器方法名称,并转为小写 + / + 当前请求的方法名称。 例如:login/add
		$path = strtolower($this->request->controller()) . '/' . $this->request->action();
		if (!in_array($path, $this->no_login)) {
			// 从logout请求头中 取出token值
			$token = Token::getRequestToken();
			// 需要登录的检测
			$ret = Token::getParse($token);
			// 把用户ID放到请求信息中取
			dump($ret);
			$this->request->get('user_id', $ret['data'] ?? '');
			// 后面不用写了，应为getParse写了返回值
			
			return $ret;
		}
	}
	
	/**
	 * 通用响应
	 * @param int $code 错误码
	 * @param string $msg 错误描述
	 * @param array $data 返回数据
	 */
	protected function response(int $code = 200, string $msg = 'success', array $data = [])
	{
		$res = ['code' => $code, 'msg' => $msg, 'data' => $data];
		// 使用原生php返回值
		// echo json_encode($res,JSON_UNESCAPED_UNICODE); die;
		
		// thinkphp的返回值
		return json($res)->send(); // send返回请求
	}
	
	/**
	 * 成功时响应
	 * @param array $data 返回数据
	 * @param int $code 错误码
	 * @param string $msg 错误描述
	 */
	protected function ok(array $data = [], int $code = 200, string $msg = 'success')
	{
		// 调用 通用响应
		$this->response($code, $msg, $data);
	}
	
	/**
	 * 失败时响应
	 * @param string $msg 错误描述
	 * @param int $code 错误码
	 * @param array $data 返回数据
	 */
	protected function fail(string $msg, int $code = 500, array $data = [])
	{
		// 调用 通用响应
		$this->response($code, $msg, $data);
	}
}
