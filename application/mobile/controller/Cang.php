<?php
namespace app\mobile\controller;

use app\mobile\logic\Cang as Logic;
use think\Log;

class Cang extends Base
{
    public function create()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getDetail() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getCangList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function createPayParams() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
