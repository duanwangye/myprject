<?php

/**
 * Created by PhpStorm.
 * 服务提供者
 * User: qissen
 * Date: 2017/6/7
 * Time: 7:36
 * 注意调用顺序，checkClientType，checkData必须先调用，才可以验证其他
 */

namespace app\core\service;
use app\core\model\Master as Model;
use tool\Common;

class Master
{

    //发送验证码，手机号
    public static function getMasterByToken($token) {
        if(!$token) {
            return false;
        }
        $user = Model::with(['userAccount'])->where([
            'token'=>$token
        ])->find();
        if($user['tokenOverTime'] < THINK_START_TIME) {
            return false;
        }
        return $user;
    }

}