<?php
namespace app\mobile\controller;

use app\mobile\logic\Ext as Logic;
use think\Log;

class Ext extends Base
{
    public function getLoginAd() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
