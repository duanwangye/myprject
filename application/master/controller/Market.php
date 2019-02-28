<?php
namespace app\master\controller;
use app\master\logic\Market as Logic;


class Market extends Base
{
    //更新一个banner
    public function activeUpdate() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function activeGetDetail() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function activeGetList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function activeActionOnlineApp() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function activeActionOfflineApp() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function activeActionOnlinePc() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function activeActionOfflinePc() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function activeActionDelete() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoGetTypeList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoUpdate() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoGetList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoGetDetail() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoActionOnline() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoActionOffline() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoPlanGetTypeList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoPlanUpdate() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoPlanGetDetail() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoPlanGetList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoPlanActionDelete() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoPlanSendUser() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function hongbaoPlanMade() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
