<?php
namespace app\master\controller;
use app\master\logic\Setting as Logic;


class Setting extends Base
{
    //更新一个banner
    public function bannerUpdate() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function bannerGetDetail() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //得到banner列表
    public function getBannerList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function bannerActionOnline() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function bannerActionOffline() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function channelUpdate() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function channelGetList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function channelGetDetail() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
