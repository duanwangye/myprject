<?php
namespace app\master\controller;
use app\master\logic\Cang as Logic;
use think\Config;


class Cang extends Base
{

    public function getCangRepayList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getCangList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function actionRepay() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
