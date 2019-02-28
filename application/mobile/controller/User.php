<?php
namespace app\mobile\controller;

use app\mobile\logic\User as Logic;
use think\Log;

class User extends Base
{
    public function checkUserByMobile() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function registerSendMobileCode() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function loginByPassword()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function loginByMobileCode()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function register() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function LoginSendMobileCode()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function resetPassword()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //充值接口
    public function recharge()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //验证接口
    public function checkInfo()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function checkInfoSendMobileCode()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getUserBankList()
    {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getUserHongbaoList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getUserInfo() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function getUserFinanceList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function logout() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
