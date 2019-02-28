<?php
namespace app\mobile\controller;

use app\mobile\logic\Finance as Logic;
use think\Log;

class Finance extends Base
{
    public function getUserFinanceList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
