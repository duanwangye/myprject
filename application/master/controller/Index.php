<?php
namespace app\master\controller;
use think\Controller;
use app\master\logic\Index as Logic;
use think\Log;

class Index extends Base
{
    public function getStat()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getCangList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }
    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
