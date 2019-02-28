<?php
namespace app\master\controller;
use app\master\logic\Loan as Logic;


class Loan extends Base
{
    //更新一个banner
    public function update() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getDetail() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getLoanList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function actionDelete() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
