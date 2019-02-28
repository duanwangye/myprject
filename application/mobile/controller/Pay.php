<?php
namespace app\mobile\controller;

use app\mobile\logic\Pay as Logic;
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
