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
use app\core\model\User as Model;
use think\Cache;
use tool\Common;

class User
{

    //发送验证码，手机号
    public static function getUserByToken($token, $osType) {
        if(!$token) {
            return false;
        }

        $user = false;
        if($osType == 1 || $osType == 2) {
            $user = Model::with(['userAccount'])->where([
                'token'=>$token
            ])->find();
            if($user['tokenOverTime'] < THINK_START_TIME) {
                return false;
            }
        }
        else if($osType == 3){
            $user = Model::with(['userAccount'])->where([
                'tokenPc'=>$token
            ])->find();
            if($user['tokenOverTimePc'] < THINK_START_TIME) {
                return false;
            }
        }
        else if($osType == 4){
            $user = Model::with(['userAccount'])->where([
                'tokenWap'=>$token
            ])->find();
            if($user['tokenOverTimeWap'] < THINK_START_TIME) {
                return false;
            }
        }

        return $user;
    }


    public static function introduceUser($fromUser, $toUser) {
        //引导奖励
        

    }

}