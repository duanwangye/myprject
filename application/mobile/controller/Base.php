<?php
namespace app\mobile\controller;

use app\core\exception\AppException;
use app\core\service\User;
use think\Config;
use think\Log;


class Base
{
    public $user;
    public $data = [];
    public $request;
    public $app;


    public function __construct()
    {

        $this->request = request();
        $this->data = json_decode($this->request->getInput(), true);
        $this->check();
        $this->app = $this->data['app'];
        $this->request->bind('data', $this->data);
        $this->request->bind('app', $this->app);

        if(in_array($this->request->path(), Config::get('tourist_path')))
        {
            //游客直接跳过
        }
        else {
            $user = User::getUserByToken($this->data['token'], $this->data['osType']);
            if(!$user) {
                throw new AppException(-1000, 'token失效，需要重新登录');
            }
            $this->request->bind('user', $user);
        }
        $this->__initialize();
    }

    public function check() {

        Log::info($this->data);
        if(!isset($this->data['apiV'])) {
            throw new AppException(-101, 'api版本号不能为空');
        }

        if(!isset($this->data['osV'])) {
            throw new AppException(-102, 'osV操作系统版本号不能为空');
        }

        if(!isset($this->data['osType'])) {
            throw new AppException(-103, 'osType操作系统类型不能为空');
        }

        if(!isset($this->data['deviceID'])) {
            throw new AppException(-104, 'deviceID宿主app的唯一标识不能为空');
        }

        if(!isset($this->data['token'])) {
            throw new AppException(-105, 'token不能为空');
        }

        if(!isset($this->data['sign'])) {
            throw new AppException(-106, 'sign不能为空');
        }

        if(!isset($this->data['time'])) {
            throw new AppException(-107, 'time不能为空');
        }

        if(!isset($this->data['ip'])) {
            throw new AppException(-108, 'ip不能为空');
        }

        if(!isset($this->data['app'])) {
            throw new AppException(-109, 'app应用数据不能为空');
        }

        if(!isset($this->data['appV'])) {
            throw new AppException(-110, 'app版本号不能为空');
        }

        if(!isset($this->data['channel'])) {
            throw new AppException(-111, 'channel不能为空');
        }

        $key = '';
        if($this->data['osType'] == 1 || $this->data['osType'] == 2) {
            $key =  Config::get('system.mobile_key');
        }
        else if($this->data['osType'] == 3) {
            $key = Config::get('system.pc_key');
        }

        $signPre = '';
        if($this->data['app'] === '') {
            $signPre = $key.$this->data['token'].$this->data['time'].$this->data['appV'].$this->data['apiV'].$this->data['osV'].$this->data['osType'].$this->data['deviceID'].$this->data['ip'].$this->data['channel'];
        }
        else if($this->data['app'] === []) {
            $signPre = $key.$this->data['token'].$this->data['time'].$this->data['appV'].$this->data['apiV'].$this->data['osV'].$this->data['osType'].$this->data['deviceID'].$this->data['ip'].$this->data['channel'].'{}';
        }
        else {
            $signPre = $key.$this->data['token'].$this->data['time'].$this->data['appV'].$this->data['apiV'].$this->data['osV'].$this->data['osType'].$this->data['deviceID'].$this->data['ip'].$this->data['channel'].json_encode($this->data['app'], JSON_UNESCAPED_UNICODE);
        }
        Log::info($signPre);
        $sign = md5($signPre);
        Log::info($sign);
        if($sign != $this->data['sign']) {
            throw new AppException(-108, 'sign签名不正确');
        }
    }



    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
}
