<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\mobile_v_2_1\logic;

use app\core\model\BankPlatform;
use think\Config;
use tool\Common;

class Ext extends Base
{
    public function getLoginAd()
    {
        return Common::rm(1, '操作成功', [
            'content'=>'http://slb.dahengdian.com/jiaqiancaifu/2017/12/28/leoAzGOCkT.png'
        ]);
    }
}