<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;

use think\Config;
use think\Log;
use think\Model;
use tool\Common;

class Cash extends Model
{
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;
    const MODE_CANG = 'MODE_CANG';//满多少送金
    const MODE_INTRODUCE = 'MODE_INTRODUCE';//邀请，满多少送金
    const MODE_INTRODUCE_BEI = 'MODE_INTRODUCE_BEI';//邀请，满多少送金
    const MODE_INTRODUCE_COMMISSION = 'MODE_INTRODUCE_COMMISSION';//邀请，满多少送金
    const STATUS_OK = 1;
    const STATUS_ERROR = 2;

    public function setMoneyAttr($value) {
        return $value * 100;
    }

    public function getMoneyAttr($value) {
        return Common::price2($value / 100);
    }

    public function getEstimateTimeAttr($value) {
        return Common::timetodate($value, 0);
    }

    public function getResultTimeAttr($value) {
        return Common::timetodate($value);
    }
}