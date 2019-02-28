<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\service;

use app\core\model\Cang as Model;
use app\core\model\Subject;
use think\Config;
use tool\Common;

class Check
{
    private $platform;
    public function __construct($platform)
    {
        $this->platform = $platform;
    }

    //$money金额小数
    public function checkCardDebit(&$msg, $trueName, $passport, $mobile, $bankNumber, $passportType = 0)
    {
        //富友平台
        if($this->platform == 'fuyou') {
            //富友平台
            vendor('fuyou.CheckCard');
            $check = new \CheckCard();
            return $check->checkCardDebit($msg, $trueName, $passport, $mobile, $bankNumber, $passportType);
        }
        //支付宝平台
        else if($this->platform == 'alipay'){

        }

        return false;
    }

    public function notify() {
        //富友平台
        if($this->platform == 'fuyou') {
            //富友平台
            vendor('fuyou.PayNotify');
            $payNotify = new \PayNotify();
            return $payNotify->mobile();
        }
        //支付宝平台
        else if($this->platform == 'alipay'){

        }
    }
}