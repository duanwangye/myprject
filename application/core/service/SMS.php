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
use think\Cache;
use think\Validate;
use Yunpian_SMS\SMS as Service;
use tool\Common;

class SMS
{

    //发送验证码，手机号
    public function sendVerificationCode($mobile = '') {

        $validate = new Validate([
            'mobile'  => 'require|length:11'
        ],[
            'name.require' => '手机号码必须填写',
            'name.length' => '手机号码格式不正确'
        ]);
        $result   = $validate->check([
            'mobile'=>$mobile
        ]);
        if(!$result){
            return Common::rm(-101, $validate->getError());
        }

        $package = Cache::get('verificationCode'.$mobile);
        if(!empty($package)) {
            if ($package['outTime'] > THINK_START_TIME) {
                return Common::rm(-102, '您发送验证码过快，请等待' . (int)($package['outTime'] - THINK_START_TIME) . '后重新发送');
            }

            if ($package['times'] > 100) {
                return Common::rm(-103, '您发送的短信太频繁了，请稍后再次验证');
            }
        }
        else {
            $package = [
                'times'=>0
            ];
        }

        /*$code = 1849970;*/
        $code = mt_rand(100000, 999999);
        $text = '【佳乾财富】您的验证码是'.$code.'。如非本人操作，请忽略本短信';
        $result = Service::send_sms($mobile, $text);
        if($result === true) {
            $package['times']++;
            $package['outTime'] = THINK_START_TIME + 30;
            $package['code'] = $code;
            Cache::set('verificationCode'.$mobile, $package, 600);
            return Common::rm(1, '操作成功');
        }
        return Common::rm(-102, $result);
    }

    //获取验证码
    public function checkVerificationCode($mobile = '', $code = '') {
        $result = Cache::get('verificationCode'.$mobile);
        if(!$result) {
            return Common::rm(-101, '验证码已经失效了');
        }
        if($result['code'] != $code) {
            return Common::rm(-102, '验证码失败');
        }
        return Common::rm(1, '验证码通过');
    }


    //发送短信
    public static function sendSMS($mobile, $text) {
        Service::send_sms($mobile, $text);
    }

    //还款发送短信
    public static function message_setCodeRepay($trueName, $title, $linkUrl) {
        return '【佳乾财富】您好，'.$trueName.'，您在投资的'.$title.'所得本息已到账，请打开app注意查收，'.$linkUrl;
    }

    //还款发送短信
    public static function message_setJiaxiYuebiao($trueName, $yearExt, $linkUrl) {
        //	【佳乾财富】亲爱的#truename#，#year#%加息券已到账请打开app查收，新app #linkurl#。新年到来之际，祝您财运旺旺，万事如意！
        //【佳乾财富】亲爱的#truename#，#year#%已到账，请打开app查收，新app地址 #linkurl#。
        return '【佳乾财富】亲爱的'.$trueName.'，'.$yearExt.'%已到账，请打开app查收，新app地址 '.$linkUrl.'。';
    }
}