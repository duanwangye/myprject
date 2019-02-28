<?php
namespace app\mobile_v_2_1\controller;

use app\mobile_v_2_1\logic\Pay as Logic;
use think\Log;

class Pay extends Base
{
    public function createOrder()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
