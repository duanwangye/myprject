<?php
namespace app\master\controller;
use app\master\logic\Master as Logic;


class Master extends Base
{
    //得到产品类型
    public function loginByPassword() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //登出
    public function logout() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
