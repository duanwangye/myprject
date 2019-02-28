<?php

/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/7
 * Time: 7:36
 */
/**
 * @apiDefine CreateUserError
 * @apiVersion 0.2.0
 *
 * @apiError code 错误标示
 * @apiError msg 错误信息
 *
 * @apiErrorExample  返回格式举例:
 *     {
 *       "code": 400,
 *       "msg": "没找到接口",
 *     }
 */
namespace app\master\logic;
use app\core\exception\AppException;
use think\Config;
use think\Request;

class Base
{
    protected $master;
    protected $app;
    protected $data;
    protected $platform;
    protected $request;
    public function __construct(Request $request) {
        $this->request = $request;
        $this->master = isset($request->master) ? $request->master : null;
        $this->data =  isset($request->data) ? $request->data : [];
        $this->app =  isset($request->app) ? $request->app : [];
        $this->platform = Config::get('platform.default_check_gateway');
        $this->_initialize();
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
    /*public function checkMember($memberID = '') {
        if($this->member['id'] != $memberID) {
            throw new AppException(-7778, '没有权限');
        }
    }*/
}