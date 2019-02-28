<?php
namespace app\mobile_v_2_1\controller;

use app\mobile_v_2_1\logic\Subject as Logic;
use think\Log;

class Subject extends Base
{
    public function create()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getSubjectList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getSubjectDetail() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getSubjectContent() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
