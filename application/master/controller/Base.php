<?php
namespace app\master\controller;
use app\core\exception\AppException;
use app\core\model\Master;
use think\Log;


class Base
{
    public $master;
    public $data = [];
    public $request;
    public $app;


    public function __construct()
    {

        header("Access-Control-Allow-Origin: *");
        $this->request = request();
        $this->data = $this->request->post();
        if(!isset($this->data['token'])) {
            throw new AppException(-1002, 'token是必传字段');
        }

        if($this->request->action() != strtolower('loginByPassword')) {
            $master = Master::get([
                'token'=>$this->data['token']
            ]);
            if(!$master) {
                throw new AppException(-1001, '不存在token');
            }
            if($master['tokenOverTime'] < THINK_START_TIME) {
                throw new AppException(-1002, '登录超时，请重新登录');
            }
            $this->request->bind('master', $master);
            if(!\app\master\logic\Master::checkAuth($master, str_replace('master/', '', $this->request->path()))) {
                throw new AppException(-1003, '没有权限');
            }
        }
        $this->request->bind('app', isset($this->data['app']) ? $this->data['app'] : []);
        $this->__initialize();
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
}
