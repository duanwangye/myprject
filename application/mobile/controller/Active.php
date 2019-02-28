<?php
namespace app\mobile\controller;

use app\mobile\logic\Active as Logic;
use think\Log;

class Active extends Base
{
    public function getActiveList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
