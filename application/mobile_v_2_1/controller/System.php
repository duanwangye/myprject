<?php
namespace app\mobile_v_2_1\controller;

use app\mobile_v_2_1\logic\System as Logic;
use think\Log;

class System extends Base
{
    public function getBankList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getConfig() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getUpgradeInfo() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
