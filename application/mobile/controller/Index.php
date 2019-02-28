<?php
namespace app\mobile\controller;

use app\mobile\logic\Index as Logic;
use think\Log;

class Index extends Base
{
    public function getIndexInfo()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
